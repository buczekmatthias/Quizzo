<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserQuizResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request): array
	{
		return [
			'slug' => $this->slug,
			'title' => $this->title,
			'is_public' => $this->is_public,
			'started_at' => $this->started_at->format('d/m/Y - H:i'),
			'finished_at' => $this->finished_at?->format('d/m/Y - H:i'),
			'participants_count' => $this->whenCounted('participants'),
			'token' => $this->token
		];
	}
}
