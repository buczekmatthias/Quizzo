<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Quiz\QuestionResource as QuizQuestionResource;
use Illuminate\Http\Request;

class QuestionResource extends QuizQuestionResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			...parent::toArray($request),
			'answers_count' => $this->whenCounted('answers'),
			'quiz' => [
				'slug' => $this->quiz->slug,
				'token' => $this->quiz->token
			]
		];
	}
}
