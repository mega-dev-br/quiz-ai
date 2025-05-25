<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('quiz_attempts', function (Blueprint $table) {
      $table->id();

      $table->foreignId('quiz_id')
        ->constrained('quizzes')
        ->cascadeOnDelete();
      $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

      $table->unsignedTinyInteger('score')->nullable();
      $table->json('option_orders')->nullable()
        ->comment('Mapeia question_id => array de option_id na ordem exibida');

      $table->timestamps();

      // Índices para consultas frequentes
      $table->index(['quiz_id', 'user_id']);
      $table->index('user_id');
      $table->index('created_at');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('quiz_attempts');
  }
};
