<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$questions = Question::select(['id'])->get();

		$questions->each(function ($question) {
			Answer::factory(fake()->numberBetween(4, 6))->for($question)->create();
		});

		DB::statement('
			UPDATE answers
			SET is_correct_answer = true
			WHERE id IN (
				SELECT DISTINCT ON (question_id) id
				FROM answers
				ORDER BY question_id, RANDOM()
			)
		');
	}
}
