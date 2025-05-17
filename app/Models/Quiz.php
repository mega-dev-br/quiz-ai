<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
  use SoftDeletes;

  protected $table = 'quiz';

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
    'usage',
  ];

  public function options()
  {
    return $this->hasMany(QuizOptions::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
