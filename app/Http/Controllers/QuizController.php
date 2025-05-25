<?php

namespace App\Http\Controllers;

use App\Idcts\CategoriesIdct;
use App\Idcts\DifficultiesIdct;
use App\Models\Options;
use App\Models\Questions;
use App\Models\QuizAttempts;
use App\Models\Quizzes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;

class QuizController extends Controller
{
  public function index()
  {
    // 1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('my-quizzes');

    return view('quiz.index');
  }



  public function myQuizzes()
  {
    // 1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('my-quizzes');

    $quizzes = Quizzes::where('user_id', Auth::id())
      ->orderBy('created_at', 'desc')
      ->paginate(10);

    $attempts = QuizAttempts::with('quiz')
      ->where('user_id', Auth::id())
      ->orderBy('created_at', 'desc')
      ->get();

    $categories = CategoriesIdct::all();
    $difficulties = DifficultiesIdct::all();

    return view('quiz.my-quizzes', compact('quizzes', 'categories', 'difficulties', 'attempts'));
  }



  public function store(Request $request)
  {
    // 1.1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('my-quizzes');

    // 1.2) Validação do upload: PDF até 100MB
    $request->validate([
      'pdf' => 'required|mimes:pdf|max:102400',
    ], [
      'pdf.required' => 'O arquivo PDF é obrigatório.',
      'pdf.mimes'    => 'O arquivo deve ser um PDF.',
      'pdf.max'      => 'O arquivo deve ter no máximo 100MB.',
    ]);

    $path   = $request->file('pdf')->getRealPath();
    $name   = $request->file('pdf')->getClientOriginalName();
    $apiKey = config('open-ai.api_key');

    // 1.3) Extrai o texto do PDF
    $parser             = new Parser();
    $pdf                = $parser->parseFile($path);
    $textExtratoDoPdf   = $pdf->getText();

    $chapters           = $request->input('chapters');
    $number_of_questions = $request->qtd;
    $category           = $request->category;
    $difficulty         = $request->difficulty;

    // 1.4) Monta o prompt combinando instrução + texto extraído
    $prompt = $this->buildQuizPrompt(
      $textExtratoDoPdf,      // todo o texto que você extraiu
      $number_of_questions,
      $difficulty,   // pode ser null
      $chapters      // pode ser null
    );

    // 1.5) Chama a API da OpenAI usando o HTTP Client do Laravel
    $response = Http::withToken($apiKey)
      ->post('https://api.openai.com/v1/chat/completions', [
        'model'    => 'gpt-4.1-nano',
        'messages' => [
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

    $chat = $response->object();

    // 1.6) upload do PDF para OpenAI
    $upload = Http::withToken($apiKey)
      ->attach('file', fopen($path, 'r'), $name)
      ->post('https://api.openai.com/v1/files', [
        'purpose' => 'user_data',
      ]);

    // 1.7) Verifica se o upload falhou
    if ($upload->failed()) {
      abort(500, 'Erro ao subir PDF: ' . $upload->body());
    }

    // 2.1) Monta o array de resposta
    $model       = $chat->model;
    $contentJson = $chat->choices[0]->message->content;
    $usage       = [
      'prompt_tokens'     => $chat->usage->prompt_tokens,
      'completion_tokens' => $chat->usage->completion_tokens,
      'total'             => $chat->usage->total_tokens,
    ];

    // 2.2) Salva o arquivo na pasta
    $filename = Str::slug(pathinfo($request->file('pdf')->getClientOriginalName(), PATHINFO_FILENAME))
      . '-' . time() . '.' . $request->file('pdf')->extension();

    $path = $request
      ->file('pdf')
      ->storeAs('quizzes/pdf', $filename, 'public');

    // 2.3) Cria o registro em `quizzes`
    $quiz = Quizzes::create([
      'title'    => $request->title, // ou outro título
      'user_id'  => Auth::id(),
      'category' => $category,
      'difficulty' => $difficulty,

      'chapters' => $chapters,
      'number_of_questions' => $number_of_questions,

      'source'   => $path,
      'model'    => $model,
      'content'  => $contentJson,        // guardamos o JSON bruto também
      'usage'    => $usage,              // coluna JSON
      'path'     => $path,
    ]);

    // 2.4) Decodifica o array de perguntas
    $questions = json_decode($contentJson, true, 512, JSON_THROW_ON_ERROR);

    // 2.5) Para cada pergunta, salva em `questions` + cada opção em `options`
    foreach ($questions as $idx => $item) {
      $question = Questions::create([
        'quiz_id' => $quiz->id,
        'text'    => $item['question'],
        'order'   => $idx + 1,
      ]);

      foreach ($item['options'] as $opt) {
        Options::create([
          'question_id' => $question->id,
          'text'        => $opt,
          'is_correct'  => ($opt === $item['answer']),
        ]);
      }
    }

    // 2.6) Retorna para a tela de meus quizzes
    return redirect()
      ->route('quiz.my-quizzes')
      ->with('success', 'Quiz criado com sucesso.');
  }

  /**
   * Monta o prompt para gerar quiz via OpenAI
   *
   * @param  string      $text               Texto extraído do PDF
   * @param  int         $numberOfQuestions  Quantidade de perguntas (5–100)
   * @param  int|null    $difficulty         1=>fácil, 2=>média, 3=>difícil
   * @param  string|null $chapters           Capítulo(s) alvo (opcional)
   * @return string
   */
  private function buildQuizPrompt(string $text, int $numberOfQuestions, ?int $difficulty = null, ?string $chapters = null): string
  {
    // Mapear dificuldade numérica para texto
    $difficultyLabels = [
      1 => 'fácil',
      2 => 'média',
      3 => 'difícil',
    ];

    // Instrução principal
    $base = "Você é um assistente que cria quizzes do zero, com perguntas objetivas de múltipla escolha.";

    // Quantidade de perguntas
    $qtd  = "Gere exatamente {$numberOfQuestions} pergunta" . ($numberOfQuestions > 1 ? 's' : '') . ".";

    // Nível de dificuldade (se informado)
    $diff = '';
    if (isset($difficulty) && isset($difficultyLabels[$difficulty])) {
      $diff = " O nível de dificuldade das questões deve ser **{$difficultyLabels[$difficulty]}**.";
    }

    // Capítulos alvo (se informado)
    $chap = '';
    if (!empty($chapters)) {
      $chap = " As perguntas devem ser baseadas exclusivamente no(s) capítulo(s): {$chapters}.";
    }

    // Regras de formatação
    $format = " Não insira introduções ou explicações. " .
      "Retorne apenas um **array JSON**" . " `question`, `options` (array de strings) e `answer` (string com a alternativa correta)." .
      " Não use marcadores como “a)”, “b)”, etc." . " Retorne 5 alternativas para cada pergunta." . " Cada pergunta deve ter exatamente 1 resposta correta.";

    // Monta tudo junto
    return trim("{$base} {$qtd}{$diff}{$chap}{$format}\n\nDados do PDF:\n{$text}");
  }




  public function show($id)
  {
    // Eager load das perguntas e de suas opções, mas já embaralhando as opções
    $quiz = Quizzes::with([
      'questions.options' => function ($query) {
        $query->inRandomOrder();
      },
    ])
      ->findOrFail($id);

    return view('quiz.show', [
      'quiz' => $quiz,
    ]);
  }



  public function result(QuizAttempts $attempt)
  {
    // 1) carrega todo o necessário
    $attempt->load([
      'quiz.questions.options',
      'answers', // inclui question_id e option_id
      'user',
    ]);

    // 2) pega o map question_id => ordem das options (option_orders vem do seu shuffle salvo)
    $orders = $attempt->option_orders ?? [];

    // 3) monta as perguntas com as opções já reordenadas
    $questions = $attempt->quiz->questions->map(function ($question) use ($attempt, $orders) {
      // ordem salva ou fallback
      $order = $orders[$question->id] ?? $question->options->pluck('id')->toArray();

      // aplica o sortBy para reordenar
      $opts = $question->options
        ->sortBy(fn($o) => array_search($o->id, $order))
        ->values();

      // resposta do usuário
      $userAnswer = $attempt->answers
        ->firstWhere('question_id', $question->id)
        ?->option_id;

      // busca a opção correta e faz null-safe
      $correctOption = $question->options->firstWhere('is_correct', true);
      $correctId     = $correctOption?->id;

      return (object) [
        'id'         => $question->id,
        'text'       => $question->text,
        'options'    => $opts,
        'userAnswer' => $userAnswer,
        'correctId'  => $correctId,
      ];
    });

    // 4) manda pro view
    return view('quiz.result', compact('attempt', 'questions'));
  }




  public function destroy($id)
  {
    // 1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('my-quizzes');

    // 2) Busca o quiz
    $quiz = Quizzes::findOrFail($id);

    // 3) Deleta o arquivo da pasta public/quizzes/pdf
    if ($quiz->source && Storage::disk('public')->exists($quiz->source)) {
      Storage::disk('public')->delete($quiz->source);
    }

    // 4) Deleta o quiz
    $quiz->delete();

    flash()->flash('success', 'Quiz deletado com sucesso.');

    return redirect()->route('quiz.my-quizzes');
  }
}
