<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('options', function (Blueprint $table) {
      $table->id();
      $table->foreignId('question_id')
        ->constrained('questions')
        ->cascadeOnDelete();

      $table->text('text');
      $table->boolean('is_correct')->default(false);

      $table->timestamps();

      // Índices para performance em filtros
      $table->index('question_id');
      $table->index('is_correct');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('options');
  }
};
