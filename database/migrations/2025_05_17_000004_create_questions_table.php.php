<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('questions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('quiz_id')
        ->constrained('quizzes')
        ->cascadeOnDelete();

      $table->text('text');
      $table->unsignedInteger('order')->default(0);

      $table->timestamps();

      // Índices compostos e únicos para ordenação e integridade
      $table->unique(['quiz_id', 'order']);
      $table->index(['quiz_id', 'order']);

      // Índice para pesquisar perguntas recentes
      $table->index('created_at');

      // Full-text para buscar por texto (opcional)
      $table->fullText('text');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('questions');
  }
};
