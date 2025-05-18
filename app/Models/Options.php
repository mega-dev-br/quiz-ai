<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
  use HasFactory;

  protected $table = 'options';
  protected $fillable = ['question_id', 'text', 'is_correct'];

  public function question()
  {
    return $this->belongsTo(Questions::class, 'question_id');
  }
}
