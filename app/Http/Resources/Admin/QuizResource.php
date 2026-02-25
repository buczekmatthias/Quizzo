<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Quiz\BaseQuizResource;
use App\Http\Resources\User\BaseProfileResource;
use Illuminate\Http\Request;

class QuizResource extends BaseQuizResource
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
			'created_at' => $this->created_at?->format('d/m/Y - H:i'),
			'deleted_at' => $this->deleted_at?->format('d/m/Y - H:i'),
			'creator' => $this->whenLoaded(
				'creator',
				BaseProfileResource::make($this->creator)
			),
			'questions_count' => $this->whenCounted('questions'),
			'categories_count' => $this->whenCounted('categories'),
		];
	}
}
