<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\BaseCategoryResource;
use App\Http\Resources\Quiz\BaseQuizResource;
use App\Models\Quiz;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		return Inertia::render('app/Profile', [
			'favoriteCategories' => Inertia::scroll(
				fn () => BaseCategoryResource::collection(
					request()->user()
							->categories()
							->select(['slug', 'name'])
							->paginate(5)
				)
			),
			'favoriteCategoriesQuizzes' => Inertia::scroll(
				fn () => BaseQuizResource::collection(
					Quiz::query()
							->userFavorite()
							->withCount(['participants'])
							->paginate(10)
				)
			),
			'finishedQuizzes' => Inertia::scroll(
				fn () => BaseQuizResource::collection(
					request()->user()
							->participatedQuizzes()
							->withCount(['participants'])
							->paginate(10)
				)
			),
		]);
	}
}
