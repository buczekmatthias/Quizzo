<?php

namespace App\Http\Resources\Quiz;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AnswerResource extends JsonResource
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
			'content' => $this->is_content_file_type
				? Storage::url($this->content)
				: $this->content,
			'is_content_file_type' => $this->is_content_file_type,
			'has_user_select_this_answer' => $this->whenLoaded(
				'users',
				$this->users->hasSole()
			)
		];
	}
}
