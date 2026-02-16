<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait QuizValidationRules
{
	public function questionsRules(): array
	{
		return [
			'array',
			'required'
		];
	}

	public function questionSlugRules(): array
	{
		return [
			'uuid',
			'required',
			Rule::exists('questions', 'slug')
		];
	}

	public function answerSlugRules(): array
	{
		return [
			'uuid',
			'nullable',
			Rule::exists('answers', 'slug')
		];
	}
}
