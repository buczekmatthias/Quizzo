<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class QuizForceDeleteService
{
	public static function forceDelete(Quiz $quiz): void
	{
		DB::transaction(function () use ($quiz) {
			Storage::deleteDirectory("questions/{$quiz->slug}");

			$answers = Answer::select(['id'])
				->whereIn(
					'question_id',
					$quiz->questions()->pluck('id')
				)
				->pluck('id');

			DB::table('answer_user')
				->where('user_id', request()->user()->id)
				->whereIn('answer_id', $answers)
				->delete();

			DB::table('answers')->whereIn('id', $answers)->delete();

			$quiz->questions()->delete();

			$quiz->participants()->detach();
			$quiz->categories()->detach();

			$quiz->forceDelete();
		}, 3);
	}
}
