<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
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
			'content' => fake()->words(fake()->numberBetween(1, 5), true),
			'is_content_file_type' => false,
			'is_correct_answer' => false,
		];
	}
}
