<?php

namespace App\Concerns;

use Illuminate\Validation\Rule;

trait CategoryValidationRules
{
	protected function newNameValidation(): array
	{
		return [
			'required',
			'string',
			Rule::unique('categories', 'name')
		];
	}

	protected function updateNameValidation(): array
	{
		return [
			'required',
			'string',
			Rule::unique('categories', 'name')->ignore($this->id)
		];
	}
}
