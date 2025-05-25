<?php

use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use App\Http\Controllers\{
  OpenAIController,
  QuizController
};

// ====== Web Routes ====== //
Route::view('/', 'welcome')->name('welcome');

Route::get('quiz/show/{id}', [QuizController::class, 'show'])->name('quiz.show');
Route::get('/quiz/result/{attempt}', [QuizController::class, 'result'])->name('quiz.result');

// ===== Auth Routes ===== //
Route::middleware(['auth', ValidateSessionWithWorkOS::class])->group(function () {
  Route::view('dashboard', 'dashboard')->name('dashboard');

  // ===== Quiz routes ===== //
  Route::group(['prefix' => 'quiz/', 'as' => 'quiz.'], function () {
    Route::get('index', [QuizController::class, 'index'])->name('index');
    Route::get('my-quizzes', [QuizController::class, 'myQuizzes'])->name('my-quizzes');
    Route::post('store', [QuizController::class, 'store'])->name('store');
    Route::get('quiz/attempts/{attempt}', [QuizController::class, 'result'])->name('quiz.result');
    Route::put('update/{id}', [QuizController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [QuizController::class, 'destroy'])->name('destroy');
  });

  // ===== Open AI ===== //
  Route::group(['prefix' => 'open-ai/', 'as' => 'open-ai.'], function () {
    Route::post('/upload', [OpenAIController::class, 'upload'])->name('upload');
    Route::get('/list', [OpenAIController::class, 'list'])->name('list');
    Route::delete('/destroy/{id}', [OpenAIController::class, 'destroy'])->name('destroy');
  });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
