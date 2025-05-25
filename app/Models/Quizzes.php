<?php

namespace App\Models;

use App\Idcts\CategoriesIdct;
use App\Idcts\DifficultiesIdct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizzes extends Model
{
  use HasFactory;

  protected $table = 'quizzes';

  protected $fillable = [
    'title',
    'user_id',
    'category',
    'difficulty',
    'source',
    'model',
    'chapters',
    'number_of_questions',
    'content',
    'usage',
  ];

  protected $casts = [
    'content' => 'array',
    'usage'   => 'array',
  ];

  // Append category description to model's array form
  protected $appends = [
    'category_description',
  ];

  /**
   * Relationship: Quiz belongs to a User
   */
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * Relationship: Quiz has many Questions, ordered by 'order'
   */
  public function questions()
  {
    return $this->hasMany(Questions::class, 'quiz_id')
      ->orderBy('order');
  }

  /**
   * Relationship: Quiz has many Attempts
   */
  public function attempts()
  {
    return $this->hasMany(QuizAttempts::class, 'quiz_id');
  }

  /**
   * Helper method to retrieve the raw category object
   */
  public function categoryData()
  {
    return CategoriesIdct::find($this->category);
  }

  /**
   * Accessor: Get the category description
   */
  public function difficultyData()
  {
    return DifficultiesIdct::find($this->difficulty);
  }
}
