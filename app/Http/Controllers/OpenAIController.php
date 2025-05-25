<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIController extends Controller
{
  /**
   * Upload a PDF file to OpenAI and get a response.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function upload(Request $request)
  {
    // 1) valida o upload
    $request->validate([
      'pdf' => 'required|file|mimes:pdf|max:51200',
    ]);

    $path   = $request->file('pdf')->getRealPath();
    $name   = $request->file('pdf')->getClientOriginalName();
    $apiKey = config('open-ai.api_key');

    if (! $apiKey) {
      abort(500, 'OPENAI_API_KEY não definido');
    }

    // 2) upload do PDF
    $upload = Http::withToken($apiKey)
      ->attach('file', fopen($path, 'r'), $name)
      ->post('https://api.openai.com/v1/files', [
        'purpose' => 'user_data',
      ]);

    if ($upload->failed()) {
      abort(500, 'Erro ao subir PDF: ' . $upload->body());
    }

    $fileId = $upload->json('id');

    // 3) instrução multimodal
    $instruction = implode("\n", [
      "Analise o PDF enviado (várias imagens).",
      "Escolha a imagem mais marcante e formule uma pergunta de múltipla escolha sobre ela.",
      "Retorne um objeto JSON com:",
      "- image: { type:'image_url', url:'…' },",
      "- question: '…',",
      "- choices: ['…','…','…','…'],",
      "- answer: '…'",
    ]);

    // 4) chamada ao endpoint /v1/responses
    $resp = Http::withToken($apiKey)
      ->timeout(120)
      ->retry(2, 100)
      ->post('https://api.openai.com/v1/responses', [
        'model' => 'gpt-4.1-nano',
        'input' => [
          [
            'role'    => 'user',
            'content' => [
              ['type' => 'input_file', 'file_id' => $fileId],
              ['type' => 'input_text', 'text' => $instruction],
            ],
          ],
        ],
      ]);

    if ($resp->failed()) {
      abort(500, 'Erro ao chamar a API: ' . $resp->body());
    }

    return $resp->json();
  }



  /**
   * List all files uploaded to OpenAI.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function list()
  {
    $apiKey = config('open-ai.api_key');

    // Listar arquivos
    $list = Http::withToken($apiKey)
      ->get('https://api.openai.com/v1/files');

    $files = $list->json();

    return view('open-ai.list', [
      'files' => $files,
    ]);
  }



  public function destroy($id)
  {
    $apiKey = config('open-ai.api_key');

    if (! $apiKey) {
      abort(500, 'OPENAI_API_KEY não definido');
    }

    // Chamada ao endpoint /v1/files/:id
    $response = Http::withToken($apiKey)
      ->delete("https://api.openai.com/v1/files/{$id}");

    if ($response->failed()) {
      abort(500, 'Erro ao deletar arquivo: ' . $response->body());
    }

    return redirect()
      ->route('open-ai.list')
      ->with('success', 'Arquivo deletado com sucesso.');
  }
}
