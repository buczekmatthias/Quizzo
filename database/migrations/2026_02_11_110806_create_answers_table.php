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
		Schema::create('answers', function (Blueprint $table) {
			$table->id();
			$table->uuid('slug')->unique();
			$table->string('content');
			$table->boolean('is_content_file_type');
			$table->boolean('is_correct_answer')->default(false);
			$table->foreignId('question_id')->constrained();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('answers');
	}
};
