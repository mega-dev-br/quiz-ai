<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
  use HasFactory;

  protected $table = 'questions';
  protected $fillable = ['quiz_id', 'text', 'order'];

  public function quiz()
  {
    return $this->belongsTo(Quizzes::class, 'quiz_id');
  }

  public function options()
  {
    return $this->hasMany(Options::class, 'question_id');
  }
}
