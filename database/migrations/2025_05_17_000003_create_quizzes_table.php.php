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
      $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

      $table->string('category')->nullable();
      $table->string('difficulty')->nullable();
      $table->string('source')->nullable();
      $table->string('model')->default('gpt-4.1-nano');
      $table->string('chapters')->nullable();
      $table->integer('number_of_questions')->default(5);
      $table->json('content')->nullable();
      $table->json('usage')->nullable();

      $table->timestamps();

      // Índices para performance
      $table->index('user_id');
      $table->index('category');
      $table->index('difficulty');
      $table->index('created_at');
      $table->index(['user_id', 'category']);
      // Para busca textual em título (opcional)
      $table->fullText('title');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('quizzes');
  }
};
