<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('quizzes', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('status')->default('draft');
      $table->string('type')->nullable();
      $table->string('category')->nullable();
      $table->string('difficulty')->nullable();
      $table->string('language')->default('pt');
      $table->string('source')->nullable();
      $table->string('source_url')->nullable();
      $table->string('model')->default('gpt-4.1-nano');
      $table->text('content')->nullable();
      $table->json('usage')->nullable();
      $table->softDeletes();
      $table->timestamps();
      $table->index(['user_id', 'status']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('quizzes');
  }
};
