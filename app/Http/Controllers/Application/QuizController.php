<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaginatedContentResource;
use App\Http\Resources\Quiz\BaseQuizResource;
use App\Models\Quiz;
use Illuminate\Http\Request;
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
							->orderBy('started_at', 'DESC')
							->orderBy('finished_at', 'DESC')
							->orderBy('title', 'ASC')
							->paginate(10)
			)
				->additional(['data_resource' => BaseQuizResource::class])
				->toArray(request())
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
