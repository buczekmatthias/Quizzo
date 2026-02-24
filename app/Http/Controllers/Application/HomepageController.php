<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\BaseCategoryResource;
use App\Http\Resources\Quiz\BaseQuizResource;
use App\Models\Quiz;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		return Inertia::render('app/Homepage', [
			...match (request()->user()) {
				null => [
					'favoriteCategories' => null,
					'favoriteCategoriesQuizzes' => null,
				],
				default => [
					'favoriteCategories' => Inertia::defer(
						fn () => BaseCategoryResource::collection(
							request()->user()
								->categories()
								->select(['slug', 'name'])
								->limit(5)
								->get()
						),
						'favoriteCategories'
					),
					'favoriteCategoriesQuizzes' => Inertia::defer(
						fn () => BaseQuizResource::collection(
							Quiz::query()
								->userFavorite()
								->limit(10)
								->get()
						),
						'favoriteCategoriesQuizzes'
					),
				]
			},
			'selectedQuizzes' => Inertia::defer(
				fn () => BaseQuizResource::collection(
					Quiz::query()
						->select(['slug', 'title', 'is_public', 'started_at', 'finished_at'])
						->hasNotFinished()
						->orderBy('started_at', 'DESC')
						->orderBy('finished_at', 'DESC')
						->orderBy('title', 'ASC')
						->limit(10)
						->get()
				),
				'quizzes'
			)
		]);
	}
}
