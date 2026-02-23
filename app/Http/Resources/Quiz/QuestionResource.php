<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		$hasImage = !is_null($this->image);

		return [
			'slug' => $this->slug,
			'content' => $this->content,
			'has_image' => $hasImage,
			'image' => $this->when(
				$hasImage,
				asset($this->image),
				null
			),
			'answers' => $this->answers->map(
				fn ($answer) => $this->when(
					$this->additional['has_finished'],
					AnswerWithCorrectAnswerResource::make($answer),
					AnswerResource::make($answer)
				)
			),
		];
	}
}
