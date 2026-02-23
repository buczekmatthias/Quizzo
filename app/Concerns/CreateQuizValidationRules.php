<?php

namespace App\Concerns;

use App\Rules\ExactlyOneCorrectAnswer;
use App\Rules\FileOrString;
use Illuminate\Validation\Rule;

trait CreateQuizValidationRules
{
	protected function titleRules(): array
	{
		return [
			'string',
			'required',
		];
	}

	protected function descriptionRules(): array
	{
		return [
			'string',
			'nullable'
		];
	}

	protected function isPublicRules(): array
	{
		return [
			'boolean',
			'required'
		];
	}

	protected function startedAtRules(): array
	{
		return [
			'required',
			'after_or_equal:' . now()->addMinutes(12)->toIso8601String()
		];
	}

	protected function finishedAtRules(): array
	{
		return [
			'nullable',
			'after_or_equal:' . now()->addDay()->startOfDay()->toIso8601String()
		];
	}

	protected function categoriesRules(): array
	{
		return [
			'array',
			'nullable'
		];
	}

	protected function categoryRules(): array
	{
		return [
			'uuid',
			Rule::exists('categories', 'slug')
		];
	}

	protected function questionsRules(): array
	{
		return [
			'array',
			'required',
			'min:2'
		];
	}

	protected function questionContentRules(): array
	{
		return [
			'string',
			'required'
		];
	}

	protected function questionImageRules(): array
	{
		return [
			'nullable',
			Rule::imageFile()->max(15360)
		];
	}

	protected function questionAnswersRules(): array
	{
		return [
			'array',
			'required',
			'min:2',
			new ExactlyOneCorrectAnswer
		];
	}

	protected function questionAnswerRules(): array
	{
		return [
			'array',
			'required'
		];
	}

	protected function answerContentRules(): array
	{
		return [
			'required',
			new FileOrString
		];
	}

	protected function answerIsContentFileTypeRules(): array
	{
		return [
			'boolean',
			'required'
		];
	}

	protected function answerIsCorrectAnswerRules(): array
	{
		return [
			'boolean',
			'required'
		];
	}
}
