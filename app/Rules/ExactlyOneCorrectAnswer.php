<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExactlyOneCorrectAnswer implements ValidationRule
{
	/**
	 * Run the validation rule.
	 *
	 * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		$correct = collect($value)->filter(fn ($item) => !empty($item['is_correct_answer']));

		if ($correct->count() !== 1) {
			$fail('There must be exactly one correct answer.');
		}
	}
}
