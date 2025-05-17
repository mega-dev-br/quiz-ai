<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\WorkOS\WorkOS;
use Smalot\PdfParser\Parser;

class QuizController extends Controller
{
  public function index()
  {
    if (Gate::denies('test-permission')) {
      abort(403, 'Acesso negado');
    }

    return view('quiz.index');
  }



  public function create()
  {
    if (Gate::denies('quiz-create')) {
      abort(403, 'Acesso negado');
    }

    return view('quiz.create');
  }



  public function store(Request $request)
  {
    // 1) Autorização: lança 403 se o usuário não tiver permissão
    Gate::authorize('quiz-create');

    // 2) Validação do upload: PDF até 10MB
    $request->validate([
      'pdf' => 'required|mimes:pdf|max:10240',
    ]);

    // 3) Extrai o texto do PDF
    $parser = new Parser();
    $pdf    = $parser->parseFile($request->file('pdf')->getRealPath());
    $text   = $pdf->getText();

    // 4) Monta o prompt combinando instrução + texto extraído
    $prompt = "Faça um questionário estilo quiz, com 10 perguntas, com 5 opções cada pergunta, devendo ser sinalizado qual é a resposta certa. Não faça introdução, apenas as perguntas numeradas. As perguntas e respostas devem estar organizadas em formato de array. Seguem dados extraídos do PDF: {$text}";

    // 5) Chama a API da OpenAI usando o HTTP Client do Laravel
    $response = Http::withToken(config('open-ai.api_key'))
      ->post('https://api.openai.com/v1/chat/completions', [
        'model'    => 'gpt-4.1-nano',
        'messages' => [
          ['role' => 'user', 'content' => $prompt],
        ],
      ]);

    $chat = $response->object();

    $res = [
      'model' => $chat->model,
      'content' => $chat->choices[0]->message->content,
      'usage' => [
        'prompt_tokens' => $chat->usage->prompt_tokens,
        'completion_tokens' => $chat->usage->completion_tokens,
        'total' => $chat->usage->total_tokens,
      ]
    ];

    return $res;
  }
}
