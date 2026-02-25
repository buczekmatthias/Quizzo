<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\User\BaseProfileResource;
use Illuminate\Http\Request;

class UserResource extends BaseProfileResource
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
			'email' => $this->email,
			'quizzes' => $this->whenLoaded(
				'quizzes',
				UserQuizResource::collection($this->quizzes)
			)
		];
	}
}
