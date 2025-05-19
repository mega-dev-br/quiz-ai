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
  public array   $questions   = [];
  public int     $currentIndex = 0;
  public array   $answers      = [];

  /**
   * Recebe o Quiz via rota/controller e prepara o array de perguntas.
   */
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

  /**
   * Marca a opção selecionada para a pergunta atual.
   */
  public function selectOption(int $optionId)
  {
    $this->answers[$this->currentIndex] = $optionId;
  }

  /**
   * Vai para a pergunta anterior, se possível.
   */
  public function previous()
  {
    if ($this->currentIndex > 0) {
      $this->currentIndex--;
    }
  }

  /**
   * Avança para a próxima pergunta, validando seleção.
   */
  public function next()
  {
    if (! isset($this->answers[$this->currentIndex])) {
      $this->dispatch('notify', [
        'type'    => 'error',
        'message' => 'Selecione uma opção antes de continuar',
      ]);
      return;
    }

    if ($this->currentIndex + 1 < count($this->questions)) {
      $this->currentIndex++;
    }
  }

  /**
   * Finaliza o quiz: cria tentativa e grava respostas.
   */
  public function finish()
  {
    // 0) Verifica se o usuário ainda está logado
    if (! Auth::check()) {
      return redirect()->route('welcome');
    }

    // 1) Cria a tentativa
    $attempt = QuizAttempts::create([
      'quiz_id' => $this->quiz->id,
      'user_id' => Auth::id(),
      'score'   => null,
    ]);

    // 2) Mapa de question_id => opção correta
    $correctMap = Options::whereIn(
      'question_id',
      collect($this->questions)->pluck('id')
    )
      ->where('is_correct', true)
      ->pluck('id', 'question_id')
      ->all();

    $correctCount = 0;
    $total        = count($this->questions);

    // 3) Para cada pergunta: salva resposta e conta se acertou
    foreach ($this->questions as $i => $q) {
      $selected = (int) $this->answers[$i] ?? null;

      Answers::create([
        'quiz_attempt_id' => $attempt->id,
        'question_id'     => (int) $q['id'],
        'option_id'       => $selected,
      ]);

      if ($selected !== null && $selected === ($correctMap[$q['id']] ?? null)) {
        $correctCount++;
      }
    }

    // 4) Calcula pontos de 0 a 100
    $scorePoints = $total
      ? intval(($correctCount * 100) / $total)
      : 0;

    // 5) Atualiza e redireciona
    $attempt->update(['score' => $scorePoints]);

    return redirect()->route('quiz.result', $attempt);
  }


  /**
   * Renderiza a view Livewire.
   */
  public function render()
  {
    return view('livewire.quiz-show', [
      'quiz'      => $this->quiz,
      'questions' => $this->questions,
    ]);
  }
}
