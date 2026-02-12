<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'slug' => fake()->unique()->uuid(),
			'content' => fake()->words(fake()->numberBetween(5, 25), true),
			'image' => null,
			'quiz_id' => Quiz::select(['id'])->inRandomOrder()->limit(1)->first()->id
		];
	}
}
