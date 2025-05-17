<?php

use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;
use App\Http\Controllers\{
  QuizController
};

// ====== Web Routes ====== //
Route::view('/', 'welcome')->name('welcome');

// ===== Auth Routes ===== //
Route::middleware(['auth', ValidateSessionWithWorkOS::class])->group(function () {
  Route::view('dashboard', 'dashboard')->name('dashboard');

  // ===== Quiz routes ===== //
  Route::group(['prefix' => 'quiz/', 'as' => 'quiz.'], function () {
    Route::get('index', [QuizController::class, 'index'])->name('index');
    Route::get('create', [QuizController::class, 'create'])->name('create');
    Route::post('store', [QuizController::class, 'store'])->name('store');
    Route::get('show/{id}', [QuizController::class, 'show'])->name('show');
    Route::get('edit/{id}', [QuizController::class, 'edit'])->name('edit');
    Route::put('update/{id}', [QuizController::class, 'update'])->name('update');
    Route::delete('destroy/{id}', [QuizController::class, 'destroy'])->name('destroy');
  });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
