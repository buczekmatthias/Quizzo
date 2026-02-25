<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryResource;
use App\Http\Requests\Category\EditCategoryResource;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\PaginatedContentResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('admin/Categories', [
			'categories' => PaginatedContentResource::make(
				Category::query()
					->select(['slug', 'name'])
					->withCount(['quizzes', 'users'])
					->orderBy('name', 'asc')
					->paginate(30)
			)
				->additional(['data_resource' => CategoryResource::class])
				->toArray(request())
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CreateCategoryResource $request): RedirectResponse
	{
		$data = $request->validated();

		Category::create([
			'slug' => Str::orderedUuid(),
			'name' => $data['name']
		]);

		return back();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(EditCategoryResource $request, Category $category): RedirectResponse
	{
		$data = $request->validated();

		$category->update([
			'name' => $data['name']
		]);

		return back(status: 303);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Category $category): RedirectResponse
	{
		DB::transaction(function () use ($category) {
			$category->quizzes()->detach();
			$category->users()->detach();

			$category->delete();
		});

		return back(status: 303);
	}
}
