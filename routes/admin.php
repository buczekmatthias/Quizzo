<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ListAnswerController;
use App\Http\Controllers\Admin\ListQuestionController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'isStaff'])->group(function () {
	Route::get('dashboard', DashboardController::class)->name('dashboard');

	Route::resource('categories', CategoryController::class)->except('create', 'edit', 'show');

	Route::resource('quizzes', QuizController::class)->only(['index', 'destroy'])->withTrashed(['destroy']);

	Route::resource('users', UserController::class)->only(['index', 'update', 'destroy']);

	Route::get('/answers', ListAnswerController::class)->name('answers.index');

	Route::get('/questions', ListQuestionController::class)->name('questions.index');
});
