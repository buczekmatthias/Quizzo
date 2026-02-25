<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Category\BaseCategoryResource;
use Illuminate\Http\Request;

class CategoryResource extends BaseCategoryResource
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
			'quizzes_count' => $this->whenCounted('quizzes'),
			'users_count' => $this->whenCounted('users'),
		];
	}
}
