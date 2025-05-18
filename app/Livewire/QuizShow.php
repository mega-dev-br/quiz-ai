<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Quizzes;
use App\Models\QuizAttempts;
use App\Models\Answers;
use Illuminate\Support\Facades\Auth;

class QuizShow extends Component
{
  // public Quizzes $quiz;
  // public array $questions;     // só o necessário para renderizar
  // public int $currentIndex = 0;
  // public array $answers = [];  // armazenará ['question_id' => option_id]

  // public function mount(Quizzes $quiz)
  // {
  //   $this->quiz = $quiz;
  //   // Carrega perguntas + opções já em array simples
  //   $this->questions = $quiz->questions
  //     ->map(fn($q) => [
  //       'id'      => $q->id,
  //       'text'    => $q->text,
  //       'options' => $q->options->map(fn($o) => ['id' => $o->id, 'text' => $o->text])->toArray(),
  //     ])
  //     ->toArray();
  // }

  // public function selectOption($optionId)
  // {
  //   // guarda a resposta para a pergunta atual
  //   $this->answers[$this->currentIndex] = $optionId;
  // }

  // public function previous()
  // {
  //   if ($this->currentIndex > 0) {
  //     $this->currentIndex--;
  //   }
  // }

  // public function next()
  // {
  //   // impede avançar sem selecionar
  //   if (! isset($this->answers[$this->currentIndex])) {
  //     $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Selecione uma opção antes de continuar']);
  //     return;
  //   }

  //   if ($this->currentIndex + 1 < count($this->questions)) {
  //     $this->currentIndex++;
  //   }
  // }

  // public function finish()
  // {
  //   // Certifique-se de que a última pergunta também foi respondida
  //   if (! isset($this->answers[$this->currentIndex])) {
  //     $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Selecione uma opção antes de finalizar']);
  //     return;
  //   }

  //   // 1) Cria tentativa
  //   $attempt = QuizAttempts::create([
  //     'quiz_id' => $this->quiz->id,
  //     'user_id' => Auth::id(),
  //     'score'   => null, // você pode calcular depois
  //   ]);

  //   // 2) Persiste cada resposta
  //   foreach ($this->answers as $index => $optionId) {
  //     Answers::create([
  //       'quiz_attempt_id' => $attempt->id,
  //       'question_id'     => $this->questions[$index]['id'],
  //       'option_id'       => $optionId,
  //     ]);
  //   }

  //   // 3) Redireciona ou mostra resultado
  //   return redirect()->route('quiz.result', $attempt);
  // }

  public function render()
  {
    return view('livewire.quiz-show');
  }
}
