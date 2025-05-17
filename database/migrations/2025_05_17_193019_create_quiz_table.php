<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('quiz', function (Blueprint $table) {
      $table->id();
      $table->string('title')->nullable();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->string('status')->default('draft');
      $table->string('type')->default('quiz');
      $table->string('category')->default('general');
      $table->string('difficulty')->default('easy');
      $table->string('language')->default('pt-BR');
      $table->string('source')->default('pdf');
      $table->string('source_url')->nullable();
      $table->string('model')->default('gpt-4.1-nano');
      $table->text('content');
      $table->json('usage')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('quiz');
  }
};
