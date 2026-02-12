<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\QuizTokenGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$isPublic = fake()->boolean();

		return [
			'slug' => fake()->unique()->uuid(),
			'title' => fake()->words(fake()->numberBetween(5, 10), true),
			'description' => fake()->boolean() ? fake()->words(fake()->numberBetween(5, 15), true) : null,
			'is_public' => $isPublic,
			'token' => $isPublic ? null : QuizTokenGenerator::generate(),
			'user_id' => User::select(['id'])->inRandomOrder()->limit(1)->first()->id,
			'started_at' => fake()->dateTimeBetween('-3 months', 'now'),
			'finished_at' => fake()->boolean() ? fake()->dateTimeBetween('now', '+5 months') : null
		];
	}
}
