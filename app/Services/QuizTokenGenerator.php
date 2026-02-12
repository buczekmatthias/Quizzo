<?php

namespace App\Services;

use App\Models\Quiz;
use Illuminate\Support\Str;

final class QuizTokenGenerator
{
	public static function generate(): string
	{
		$token = Str::random();
		$existingTokens = Quiz::select('token')->pluck('token');

		while ($existingTokens->contains($token)) {
			$token = Str::random();
		}

		return $token;
	}
}
