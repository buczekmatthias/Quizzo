<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AnswerResource;
use App\Http\Resources\PaginatedContentResource;
use App\Models\Answer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListAnswerController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request): Response
	{
		$onlyCorrectAnswers = $request->boolean('onlyCorrectAnswers', false);
		$onlyFileAnswers = $request->boolean('onlyFileAnswers', false);

		return Inertia::render('admin/Answers', [
			'answers' => PaginatedContentResource::make(
				Answer::query()
					->select(['slug', 'content', 'is_content_file_type', 'is_correct_answer', 'question_id'])
					->with(['question:id,quiz_id', 'question.quiz:id,slug,token'])
					->when(
						$onlyCorrectAnswers,
						fn ($q) => $q->where('answers.is_correct_answer', true)
					)
					->when(
						$onlyFileAnswers,
						fn ($q) => $q->where('answers.is_content_file_type', true)
					)
					->orderBy('is_content_file_type', 'asc')
					->orderBy('content', 'asc')
					->paginate(30)
			)
				->additional(['data_resource' => AnswerResource::class])
				->toArray($request),
			'filters' => [
				'correct_only' => $onlyCorrectAnswers,
				'files_only' => $onlyFileAnswers
			]
		]);
	}
}
