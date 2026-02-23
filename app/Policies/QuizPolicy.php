<?php

namespace App\Policies;

use App\Enum\UserRole;
use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Quiz $quiz, ?string $token): bool
	{
		if ($quiz->is_public) {
			return true;
		}

		if (!$token) {
			return false;
		}

		return $quiz->token === $token;
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Quiz $quiz, ?string $token): bool
	{
		if (!$this->view($user, $quiz, $token)) {
			return false;
		}

		if ($user->role !== UserRole::USER) {
			return true;
		}

		return !$quiz->finished_at?->isPast() && $quiz->user_id === $user->id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Quiz $quiz): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Quiz $quiz): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Quiz $quiz): bool
	{
		return false;
	}
}
