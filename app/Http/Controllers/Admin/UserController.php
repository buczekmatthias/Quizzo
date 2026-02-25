<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateRoleRequest;
use App\Http\Resources\Admin\UserResource;
use App\Http\Resources\PaginatedContentResource;
use App\Models\User;
use App\Services\QuizForceDeleteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('admin/Users', [
			'users' => PaginatedContentResource::make(
				User::query()
					->select(['id', 'username', 'name', 'email', 'role'])
					->with([
						'quizzes' => fn ($q) => $q
							->select(['user_id', 'title', 'slug', 'started_at', 'finished_at', 'id', 'token', 'is_public'])
							->withCount(['participants']),
					])
					->orderInHierarchy()
					->orderBy('name', 'desc')
					->orderBy('username', 'asc')
					->paginate(30)
			)
				->additional(['data_resource' => UserResource::class])
				->toArray(request()),
			'can_manage' => request()->user()->isAdmin(),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(UpdateRoleRequest $request, User $user): RedirectResponse
	{
		$data = $request->validated();

		$user->update([
			'role' => $data['role']
		]);

		return back(status: 303);
	}

	public function destroy(User $user): RedirectResponse
	{
		DB::transaction(function () use ($user) {
			$user->quizzes->each(
				fn ($q) => QuizForceDeleteService::forceDelete($q)
			);

			$user->participatedQuizzes()->detach();
			$user->answers()->delete();
			$user->categories()->detach();

			$user->delete();
		}, 3);

		return back(status: 303);
	}
}
