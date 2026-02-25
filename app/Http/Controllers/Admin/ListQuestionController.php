<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedContentResource;
use App\Http\Resources\Admin\QuestionResource;
use App\Models\Question;
use Inertia\Inertia;
use Inertia\Response;

class ListQuestionController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		return Inertia::render('admin/Questions', [
			'questions' => PaginatedContentResource::make(
				Question::query()
					->select(['slug', 'content', 'image', 'quiz_id'])
					->with(['quiz:id,slug,token'])
					->withCount(['answers'])
					->orderBy('content', 'asc')
					->paginate(30)
			)
				->additional(['data_resource' => QuestionResource::class])
				->toArray(request()),
		]);
	}
}
