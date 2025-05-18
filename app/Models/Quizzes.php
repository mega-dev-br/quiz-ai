<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quizzes extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'quizzes';

  protected $fillable = [
    'title',
    'user_id',
    'status',
    'type',
    'category',
    'difficulty',
    'language',
    'source',
    'source_url',
    'model',
    'content',
    'usage'
  ];

  protected $casts = [
    'usage'   => 'array',
    'content' => 'string',
  ];

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function questions()
  {
    return $this->hasMany(Questions::class, 'quiz_id')->orderBy('order');
  }

  public function attempts()
  {
    return $this->hasMany(QuizAttempts::class, 'quiz_id');
  }
}
