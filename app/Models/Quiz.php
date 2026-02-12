<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
	/** @use HasFactory<\Database\Factories\QuizFactory> */
	use HasFactory, SoftDeletes;

	public function getRouteKeyName()
	{
		return 'slug';
	}

	protected $fillable = [
		'slug',
		'title',
		'description',
		'token',
		'is_public',
		'started_at',
		'finished_at'
	];

	public function questions(): HasMany
	{
		return $this->hasMany(Question::class);
	}

	public function creator(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function participants(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'quiz_user');
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'category_quiz');
	}
}
