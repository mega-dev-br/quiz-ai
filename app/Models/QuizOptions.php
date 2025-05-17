<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizOptions extends Model
{
  use SoftDeletes;

  protected $table = 'quiz_options';

  protected $fillable = [
    'quiz_id',
    'option_text',
    'is_correct',
  ];

  public function quiz()
  {
    return $this->belongsTo(Quiz::class);
  }
}
