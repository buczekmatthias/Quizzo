<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Quiz\AnswerWithCorrectAnswerResource;
use Illuminate\Http\Request;

class AnswerResource extends AnswerWithCorrectAnswerResource
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
			'quiz' => $this->whenLoaded(
				'question',
				[
					'slug' => $this->question->quiz->slug,
					'token' => $this->question->quiz->token
				]
			)
		];
	}
}
