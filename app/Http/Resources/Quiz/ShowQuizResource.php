<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;

class ShowQuizResource extends BaseQuizResource
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
			'description' => $this->description,
			'can_be_done' => !$this->finished_at?->isPast() && !$this->additional['did_user_do'],
			'did_user_do' => $this->additional['did_user_do'],
			'token' => $this->when(
				!is_null($this->token),
				$this->token
			)
		];
	}
}
