<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('quizzes', function (Blueprint $table) {
			$table->id();
			$table->uuid('slug')->unique();
			$table->string('title');
			$table->string('description')->nullable();
			$table->string('token')->nullable();
			$table->boolean('is_public')->default(true);
			$table->foreignId('user_id')->constrained();
			$table->timestamp('started_at')->default(now());
			$table->timestamp('finished_at')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('quizzes');
	}
};
