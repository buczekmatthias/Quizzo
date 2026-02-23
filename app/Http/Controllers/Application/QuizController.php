<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateQuizRequest;
use App\Http\Requests\Quiz\SubmitQuizRequest;
use App\Http\Resources\PaginatedContentResource;
use App\Http\Resources\Quiz\AccessQuizResource;
use App\Http\Resources\Quiz\BaseQuizResource;
use App\Http\Resources\Quiz\QuestionResource;
use App\Http\Resources\Quiz\ShowQuizResource;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Quiz;
use App\Services\QuizTokenGenerator;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('app/quiz/Index', [
			'paginatedQuizzes' => PaginatedContentResource::make(
				Quiz::query()
							->select(['slug', 'title', 'is_public', 'started_at', 'finished_at'])
							->hasNotFinished()
							->orderBy('started_at', 'DESC')
							->orderBy('finished_at', 'DESC')
							->orderBy('title', 'ASC')
							->paginate(10)
			)
				->additional(['data_resource' => BaseQuizResource::class])
				->toArray(request())
		]);
	}

	public function create(): Response
	{
		return Inertia::render('app/quiz/Create', [
			'categories' => Inertia::defer(
				fn () => Category::select(['slug', 'name'])
					->orderBy('name', 'asc')
					->get()
			),
			'max_answers_count' => config('constants.max_answers_count')
		]);
	}

	public function store(CreateQuizRequest $request): RedirectResponse
	{
		$data = $request->validated();

		$quizSlug = Str::orderedUuid();

		DB::transaction(function () use ($request, $data, $quizSlug) {
			$questionsData = collect($request->questions)->map(fn ($q) => [
				'slug' => Str::orderedUuid(),
				'content' => $q['content'],
				'image' => isset($q['image'])
					? Storage::put("questions/{$quizSlug}", $q['image'])
					: null,
				'answers' => collect($q['answers'])->map(fn ($answer) => [
					'slug' => Str::orderedUuid(),
					'content' => $answer['is_content_file_type']
						? Storage::put("questions/{$quizSlug}/answers", $answer['content'])
						: $answer['content'],
					'is_content_file_type' => $answer['is_content_file_type'],
					'is_correct_answer' => $answer['is_correct_answer'],
				]),
			]);

			$quiz = Quiz::make([
				'slug' => $quizSlug,
				'title' => $data['title'],
				'description' => $data['description'],
				'is_public' => $data['is_public'],
				'token' => $data['is_public'] ? null : QuizTokenGenerator::generate(),
				'started_at' => Carbon::parse($data['started_at']),
				'finished_at' => $data['finished_at'] ? Carbon::parse($data['finished_at']) : null,
			]);

			$quiz->user_id = $request->user()->id;

			$quiz->save();

			if (isset($data['categories']) && count($data['categories']) > 0) {
				$categories = Category::select(['id'])
					->whereIn('slug', $data['categories'])
					->pluck('id');

				$quiz->categories()->attach($categories);
			}

			$questions = $quiz->questions()->createMany(
				$questionsData->map(fn ($q) => [
					'slug' => $q['slug'],
					'content' => $q['content'],
					'image' => $q['image'],
				])->toArray()
			);

			$answers = $questions->flatMap(
				fn ($question, $index) => $questionsData[$index]['answers']->map(fn ($answer) => [
					'slug' => $answer['slug'],
					'question_id' => $question->id,
					'content' => $answer['content'],
					'is_content_file_type' => $answer['is_content_file_type'],
					'is_correct_answer' => $answer['is_correct_answer'],
				])
			);

			Answer::insert($answers->toArray());
		});

		return to_route('quizzes.show', ['quiz' => $quizSlug]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function submit(SubmitQuizRequest $request, Quiz $quiz, ?string $token = null): RedirectResponse
	{
		Gate::authorize('view', [$quiz, $token]);

		$data = $request->validated();

		DB::transaction(function () use ($quiz, $data) {
			$answers = Answer::select(['id'])->whereIn(
				'slug',
				collect($data['questions'])->pluck('answer_selected_slug')->filter(fn ($s) => $s !== null)
			)->get();

			request()->user()->answers()->attach($answers);

			$now = now();
			$quiz->participants()->attach(request()->user(), ['created_at' => $now, 'updated_at' => $now]);
		});

		return back();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Quiz $quiz, ?string $token = null): Response | RedirectResponse
	{
		if (!Auth::check()) {
			session(['redirect_to' => route('quizzes.show', ['quiz' => $quiz->slug, 'token' => $token])]);

			return to_route('login');
		}

		if (!$quiz->is_public && !$token) {
			return Inertia::render('app/quiz/AccessQuiz', [
				'quiz' => AccessQuizResource::make($quiz)
			]);
		}

		Gate::authorize('view', [$quiz, $token]);

		// TODO: Count correct answers and give score
		$answersColumns = match ($quiz->finished_at?->isPast()) {
			true => 'id,slug,content,is_content_file_type,is_correct_answer,question_id',
			default => 'id,slug,content,is_content_file_type,question_id'
		};

		$relationships = [
			"answers:{$answersColumns}"
		];

		$hasUserFinishedQuiz = $quiz->participants()->where('user_id', request()->user()->id)->exists();

		if ($hasUserFinishedQuiz) {
			$relationships = [
				...$relationships,
				'answers.users' => fn ($q) => $q
					->select(['username'])
					->where('users.id', request()->user()->id)
			];
		}

		$questions = $quiz
						->questions()
						->select(['id', 'slug', 'content', 'image'])
						->with($relationships)
						->orderBy('id', 'asc')
						->get();

		return Inertia::render('app/quiz/Show', [
			'quiz' => ShowQuizResource::make($quiz)
				->additional(['did_user_do' => $hasUserFinishedQuiz])
				->toArray(request()),
			'questions' => Inertia::defer(
				fn () => $questions->map(
					fn ($question) => QuestionResource::make($question)
						->additional(['has_finished' => $quiz->finished_at?->isPast()])
						->toArray(request())
				)
			),
			'permissions' => [
				'finish' => request()->user()->can('update', $quiz)
			]
		]);
	}

	public function update(Quiz $quiz, ?string $token = null): RedirectResponse
	{
		Gate::authorize('update', $quiz);

		$quiz->update([
			'finished_at' => now()
		]);

		return back(status: 303);
	}
}
