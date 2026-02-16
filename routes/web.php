<?php

use App\Http\Controllers\Application\HomepageController;
use App\Http\Controllers\Application\QuizController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomepageController::class)->name('home');

Route::resource('quizzes', QuizController::class)->only(['index', 'create', 'store']);
Route::get('/quizzes/{quiz}/{token?}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes/{quiz}/{token?}', [QuizController::class, 'submit'])->name('quizzes.submit');
Route::patch('/quizzes/{quiz}/{token?}', [QuizController::class, 'update'])->name('quizzes.update');

Route::get('dashboard', function () {
	return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/settings.php';
