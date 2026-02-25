<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait QuizValidationRules
{
	protected function questionsRules(): array
	{
		return [
			'array',
			'required'
		];
	}

	protected function questionSlugRules(): array
	{
		return [
			'uuid',
			'required',
			Rule::exists('questions', 'slug')
		];
	}

	protected function answerSlugRules(): array
	{
		return [
			'uuid',
			'nullable',
			Rule::exists('answers', 'slug')
		];
	}
}
