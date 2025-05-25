<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempts extends Model
{
  use HasFactory;

  protected $table = 'quiz_attempts';

  protected $fillable = [
    'quiz_id',
    'user_id',
    'score',
    'option_orders',
  ];

  protected $casts = [
    'option_orders' => 'array',
  ];

  protected $with = [
    'quiz',
    'user',
  ];

  public function quiz()
  {
    return $this->belongsTo(Quizzes::class, 'quiz_id');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function answers()
  {
    return $this->hasMany(Answers::class, 'quiz_attempt_id');
  }
}
