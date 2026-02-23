<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;

class CategoryWithFavoriteResource extends BaseCategoryResource
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
			'is_favorite' => $this->users->hasSole(),
			'quizzes_count' => $this->whenCounted('quizzes')
		];
	}
}
