<?php

namespace App\Livewire;

use App\Models\Answers;
use App\Models\Options;
use App\Models\QuizAttempts;
use App\Models\Quizzes;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizShow extends Component
{
  public Quizzes $quiz;
  public array   $questions    = [];
  public int     $currentIndex = 0;
  public array   $answers      = [];

  public function mount(Quizzes $quiz)
  {
    $this->quiz = $quiz;
    $this->questions = $quiz->questions
      ->map(fn($q) => [
        'id'      => $q->id,
        'text'    => $q->text,
        'options' => $q->options
          ->shuffle()
          ->map(fn($o) => ['id' => $o->id, 'text' => $o->text])
          ->toArray(),
      ])->toArray();
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
    // 1) monta o mapa de ordens
    $orders = [];
    foreach ($this->questions as $q) {
      $orders[$q['id']] = array_column($q['options'], 'id');
    }

    // 2) cria tentativa incluindo option_orders
    $attempt = QuizAttempts::create([
      'quiz_id'      => $this->quiz->id,
      'user_id'      => Auth::id(),
      'score'        => null,
      'option_orders' => $orders,
    ]);

    // 3) salva respostas e conta acertos
    $correctMap = Options::whereIn(
      'question_id',
      array_column($this->questions, 'id')
    )
      ->where('is_correct', true)
      ->pluck('id', 'question_id')
      ->all();

    $correctCount = 0;
    foreach ($this->questions as $i => $q) {
      $selected = $this->answers[$i] ?? null;
      Answers::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id'     => $q['id'],
        'option_id'       => $selected,
      ]);
      if ($selected === ($correctMap[$q['id']] ?? null)) {
        $correctCount++;
      }
    }

    // 4) calcula pontos 0–100
    $total = count($this->questions);
    $points = $total
      ? intval($correctCount * 100 / $total)
      : 0;
    $attempt->update(['score' => $points]);

    return redirect()->route('quiz.result', $attempt);
  }

  public function render()
  {
    return view('livewire.quiz-show', [
      'quiz'      => $this->quiz,
      'questions' => $this->questions,
    ]);
  }
}
