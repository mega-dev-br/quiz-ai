<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('answers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
      $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
      $table->foreignId('option_id')->constrained('options')->cascadeOnDelete();
      $table->timestamps();
      $table->unique(['quiz_attempt_id', 'question_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('answers');
  }
};
