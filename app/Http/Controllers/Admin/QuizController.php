<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\QuizResource;
use App\Http\Resources\PaginatedContentResource;
use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('admin/Quizzes', [
			'allQuizzes' => PaginatedContentResource::make(
				Quiz::query()
					->select(['slug', 'title', 'is_public', 'started_at', 'finished_at', 'created_at', 'deleted_at', 'user_id'])
					->with(['creator:id,name,username'])
					->withCount(['questions', 'participants', 'categories'])
					->orderBy('created_at', 'desc')
					->orderBy('title', 'asc')
					->withTrashed()
					->paginate(30)
			)
				->additional(['data_resource' => QuizResource::class])
				->toArray(request()),
			'permissions' => [
				'restore' => request()->user()->isAdmin(),
				'forceDelete' => request()->user()->isAdmin(),
			]
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quiz $quiz): RedirectResponse
	{
		$action = request()->input('action', 'delete');

		Gate::authorize($action, $quiz);

		match ($action) {
			'delete' => $quiz->delete(),
			'restore' => $quiz->restore(),
			'forceDelete' => DB::transaction(function () use ($quiz) {
				Storage::deleteDirectory("questions/{$quiz->slug}");

				$answers = Answer::select(['id'])
					->whereIn(
						'question_id',
						$quiz->questions()->pluck('id')
					)
					->pluck('id');

				DB::table('answer_user')
					->where('user_id', request()->user()->id)
					->whereIn('answer_id', $answers)
					->delete();

				DB::table('answers')->whereIn('id', $answers)->delete();

				$quiz->questions()->delete();

				$quiz->participants()->detach();
				$quiz->categories()->detach();

				$quiz->forceDelete();
			}, 3),
			default => ''
		};

		return back(status: 303);
	}
}
