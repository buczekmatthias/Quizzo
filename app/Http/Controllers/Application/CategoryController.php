<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryWithFavoriteResource;
use App\Http\Resources\PaginatedContentResource;
use App\Http\Resources\Quiz\BaseQuizResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('app/category/Index', [
			'paginatedCategories' => PaginatedContentResource::make(
				Category::select(['id', 'slug', 'name'])
					->with([
						'users' => fn ($query) => $query
							->select(['username'])
							->where('id', request()->user()->id)
					])
					->withCount(['quizzes'])
					->orderBy('name', 'asc')
					->paginate(30)
			)
				->additional(['data_resource' => CategoryWithFavoriteResource::class])
				->toArray(request())
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Category $category): Response
	{
		$category
			->load([
				'users' => fn ($query) => $query
							->select(['username'])
							->where('id', request()->user()->id),
			])
			->loadCount(['quizzes']);

		return Inertia::render('app/category/Show', [
			'category' => CategoryWithFavoriteResource::make($category),
			'quizzes' => Inertia::defer(
				fn () => PaginatedContentResource::make(
					$category
						->quizzes()
						->paginate(25)
				)
						->additional(['data_resource' => BaseQuizResource::class])
						->toArray(request())
			)
		]);
	}

	public function toggleFavorite(Category $category): RedirectResponse
	{
		if ($category->users()->where('user_id', request()->user()->id)->exists()) {
			$category->users()->detach(request()->user()->id);
		} else {
			$category->users()->attach(request()->user()->id);
		}

		return back(status: 303);
	}
}
