<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'isStaff'])->group(function () {
	Route::get('dashboard', DashboardController::class)->name('dashboard');
});
