<?php

namespace App\Http\Controllers;

use App\Models\Options;
use App\Models\Questions;
use App\Models\Quizzes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

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

    return view('quiz.my-quizzes', compact('quizzes'));
  }



  public function store(Request $request)
  {
    // 1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('my-quizzes');

    // 2) Validação do upload: PDF até 100MB
    $request->validate([
      'pdf' => 'required|mimes:pdf|max:102400',
    ], [
      'pdf.required' => 'O arquivo PDF é obrigatório.',
      'pdf.mimes'    => 'O arquivo deve ser um PDF.',
      'pdf.max'      => 'O arquivo deve ter no máximo 100MB.',
    ]);

    // 3) Extrai o texto do PDF
    $parser = new Parser();
    $pdf    = $parser->parseFile($request->file('pdf')->getRealPath());
    $text   = $pdf->getText();

    // 4) Monta o prompt combinando instrução + texto extraído
    $prompt = "Faça um questionário estilo quiz, com 10 perguntas, com 5 opções cada pergunta, devendo ser sinalizado qual é a resposta certa. Não faça introdução, apenas as perguntas numeradas. As perguntas e respostas devem estar organizadas em formato de array. As chaves deste array devem vir em inglês. Seguem dados extraídos do PDF: {$text}";

    // 5) Chama a API da OpenAI usando o HTTP Client do Laravel
    $response = Http::withToken(config('open-ai.api_key'))
      ->post('https://api.openai.com/v1/chat/completions', [
        'model'    => 'gpt-4.1-nano',
        'messages' => [
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

    $chat = $response->object();

    // vamos supor que, ao final, você tenha:
    $model       = $chat->model;
    $contentJson = $chat->choices[0]->message->content;
    $usage       = [
      'prompt_tokens'     => $chat->usage->prompt_tokens,
      'completion_tokens' => $chat->usage->completion_tokens,
      'total'             => $chat->usage->total_tokens,
    ];

    // 1) Cria o registro em `quizzes`
    /** @var Quizzes $quiz */
    $quiz = Quizzes::create([
      'title'    => $request->file('pdf')->getClientOriginalName(), // ou outro título
      'user_id'  => Auth::id(),
      'status'   => 'published',         // ou draft
      'type'     => 'automated',         // ex.: manual/automated
      'category' => null,
      'difficulty' => null,
      'language' => 'pt',
      'source'   => 'pdf',
      'source_url' => null,
      'model'    => $model,
      'content'  => $contentJson,        // guardamos o JSON bruto também
      'usage'    => $usage,              // coluna JSON
    ]);

    // 2) Decodifica o array de perguntas
    $questions = json_decode($contentJson, true, 512, JSON_THROW_ON_ERROR);

    // 3) Para cada pergunta, salva em `questions` + cada opção em `options`
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

    // 4) Retorna o quiz completo (com relações carregadas, se desejar)
    return $quiz->load('questions.options');
  }



  public function show($id)
  {
    Gate::authorize('my-quizzes');

    $quiz = Quizzes::with('questions.options')->findOrFail($id);

    // Passamos $quiz **somente** para o Blade wrapper.
    return view('quiz.show', [
      'quiz' => $quiz,
    ]);
  }
}
