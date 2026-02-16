<?php

namespace App\Http\Requests\Quiz;

use App\Concerns\QuizValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class SubmitQuizRequest extends FormRequest
{
	use QuizValidationRules;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return $this->user() !== null;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'questions' => $this->questionsRules(),
			'questions.*.slug' => $this->questionSlugRules(),
			'questions.*.answer_selected_slug' => $this->answerSlugRules(),
		];
	}
}
