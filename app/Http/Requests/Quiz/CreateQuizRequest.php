<?php

namespace App\Http\Requests\Quiz;

use App\Concerns\CreateQuizValidationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateQuizRequest extends FormRequest
{
	use CreateQuizValidationRules;

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'title' => $this->titleRules(),
			'description' => $this->descriptionRules(),
			'is_public' => $this->isPublicRules(),
			'started_at' => $this->startedAtRules(),
			'finished_at' => $this->finishedAtRules(),
			'categories' => $this->categoriesRules(),
			'categories.*' => $this->categoryRules(),
			'questions' => $this->questionsRules(),
			'questions.*.content' => $this->questionContentRules(),
			'questions.*.image' => $this->questionImageRules(),
			'questions.*.answers' => $this->questionAnswersRules(),
			'questions.*.answers.*' => $this->questionAnswerRules(),
			'questions.*.answers.*.content' => $this->answerContentRules(),
			'questions.*.answers.*.is_content_file_type' => $this->answerIsContentFileTypeRules(),
			'questions.*.answers.*.is_correct_answer' => $this->answerIsCorrectAnswerRules(),
		];
	}
}
