<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Quizzes;
use App\Models\QuizAttempts;
use App\Models\Answers;
use Illuminate\Support\Facades\Auth;

class QuizShow extends Component
{
  public Quizzes $quiz;
  public array   $questions = [];
  public int     $currentIndex = 0;
  public array   $answers = [];

  public function mount(Quizzes $quiz)
  {
    $this->quiz = $quiz;

    $this->questions = $quiz->questions
      ->map(fn($q) => [
        'id'      => $q->id,
        'text'    => $q->text,
        'options' => $q->options
          ->map(fn($o) => ['id' => $o->id, 'text' => $o->text])
          ->toArray(),
      ])
      ->toArray();
  }

  public function selectOption(int $optionId)
  {
    $this->answers[$this->currentIndex] = $optionId;
  }

  public function previous()
  {
    if ($this->currentIndex > 0) {
      $this->currentIndex--;
    }
  }

  public function next()
  {
    if (! isset($this->answers[$this->currentIndex])) {
      $this->dispatchBrowserEvent('notify', [
        'type'    => 'error',
        'message' => 'Selecione uma opção antes de continuar',
      ]);
      return;
    }

    if ($this->currentIndex + 1 < count($this->questions)) {
      $this->currentIndex++;
    }
  }

  public function finish()
  {
    if (! isset($this->answers[$this->currentIndex])) {
      $this->dispatchBrowserEvent('notify', [
        'type'    => 'error',
        'message' => 'Selecione uma opção antes de finalizar',
      ]);
      return;
    }

    $attempt = QuizAttempts::create([
      'quiz_id' => $this->quiz->id,
      'user_id' => Auth::id(),
      'score'   => null,
    ]);

    foreach ($this->answers as $index => $optionId) {
      Answers::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id'     => $this->questions[$index]['id'],
        'option_id'       => $optionId,
      ]);
    }

    return redirect()->route('quiz.result', $attempt);
  }

  public function render()
  {
    // *ATENÇÃO*: o nome da view deve bater com o arquivo abaixo
    return view('livewire.quiz-show');
  }
}
