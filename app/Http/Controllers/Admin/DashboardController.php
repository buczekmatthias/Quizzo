<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
	/**
	 * Handle the incoming request.
	 */
	public function __invoke(): Response
	{
		$now = now();
		$startOfMonth = $now->copy()->startOfMonth();
		$endOfMonth = $now->copy()->endOfMonth();

		return Inertia::render('admin/Dashboard', [
			'quizzes_count' => [
				'total' => Quiz::count(),
				'current_month' => Quiz::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
				'taken' => DB::table('quiz_user')->count(),
				'taken_current_month' => DB::table('quiz_user')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count()
			],
			'users_count' => [
				'total' => User::count(),
				'current_month' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count()
			],
		]);
	}
}
