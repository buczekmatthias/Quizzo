<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\QuizResource;
use App\Http\Resources\PaginatedContentResource;
use App\Models\Quiz;
use App\Services\QuizForceDeleteService;
use Illuminate\Http\RedirectResponse;
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
			'forceDelete' => QuizForceDeleteService::forceDelete($quiz),
			default => ''
		};

		return back(status: 303);
	}
}
