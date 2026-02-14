<?php

use App\Http\Controllers\Application\HomepageController;
use App\Http\Controllers\Application\QuizController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomepageController::class)->name('home');

Route::resource('quizes', QuizController::class)->except(['show']);
Route::get('/quizes/{quiz}/{token?}', [QuizController::class, 'show'])->name('quizes.show');

Route::get('dashboard', function () {
	return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/settings.php';
