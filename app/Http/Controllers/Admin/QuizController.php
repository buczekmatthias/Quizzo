<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(): Response
	{
		return Inertia::render('admin/Quizzes');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Quiz $quiz): RedirectResponse
	{
		return back();
	}
}
