<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;

class AnswerWithCorrectAnswerResource extends AnswerResource
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
			'is_correct_answer' => $this->is_correct_answer
		];
	}
}
