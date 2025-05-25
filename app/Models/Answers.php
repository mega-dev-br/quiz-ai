<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
  use HasFactory;

  protected $table = 'answers';

  protected $fillable = [
    'quiz_attempt_id',
    'question_id',
    'option_id',
  ];

  public function attempt()
  {
    return $this->belongsTo(QuizAttempts::class, 'quiz_attempt_id');
  }

  public function question()
  {
    return $this->belongsTo(Questions::class, 'question_id');
  }

  public function option()
  {
    return $this->belongsTo(Options::class, 'option_id');
  }
}
