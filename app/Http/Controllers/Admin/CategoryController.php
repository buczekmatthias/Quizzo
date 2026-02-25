<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('admin/Categories', []);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		return back();
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Category $category): RedirectResponse
	{
		return back();
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Category $category): RedirectResponse
	{
		return back();
	}
}
