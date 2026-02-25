<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
	/** @use HasFactory<\Database\Factories\UserFactory> */
	use HasFactory, Notifiable;

	public function getRouteKeyName()
	{
		return 'username';
	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var list<string>
	 */
	protected $fillable = [
		'name',
		'username',
		'email',
		'password',
		'role'
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var list<string>
	 */
	protected $hidden = [
		'password',
		// 'two_factor_secret',
		// 'two_factor_recovery_codes',
		'remember_token',
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			'role' => UserRole::class
			// 'two_factor_confirmed_at' => 'datetime',
		];
	}

	public function quizzes(): HasMany
	{
		return $this->hasMany(Quiz::class);
	}

	public function participatedQuizzes(): BelongsToMany
	{
		return $this->belongsToMany(Quiz::class, 'quiz_user');
	}

	public function answers(): BelongsToMany
	{
		return $this->belongsToMany(Answer::class, 'answer_user');
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'category_user');
	}

	public function scopeOrderInHierarchy(Builder $query, string $order = 'asc'): Builder
	{
		return $query->orderByRaw("array_position("."'{" . implode(',', array_reverse(array_column(UserRole::cases(), 'value'))) . "}'"."::text[],role) {$order}");
	}

	public function isStaff(): bool
	{
		return in_array(
			$this->role,
			[
				UserRole::MOD,
				UserRole::ADMIN
			]
		);
	}

	public function isAdmin(): bool
	{
		return $this->role === UserRole::ADMIN;
	}
}
