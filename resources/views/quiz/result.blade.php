{{-- resources/views/quiz/result.blade.php --}}
<x-layouts.app.quiz :title="__('Resultado do Quiz')" :quiz="$attempt->quiz">
  <x-slot name="content">
    <div class="max-w-2xl mx-auto space-y-8">

      {{-- Card Principal --}}
      <div
        class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-lg overflow-hidden">
        {{-- Cabeçalho em Gradiente --}}
        <div class="bg-gradient-to-r from-slate-600 to-blue-600 p-6 flex items-center space-x-6">
          {{-- Texto à esquerda --}}
          <div class="text-white flex-1">
            <h2 class="text-3xl font-extrabold">{{ __('Resultado do Quiz') }}</h2>
            <p class="mt-2 text-blue-100">
              <strong>{{ __('Quiz:') }}</strong> {{ $attempt->quiz->title }}<br>
              <strong>{{ __('Usuário:') }}</strong> {{ $attempt->user->name }}<br>
              <strong>{{ __('Data:') }}</strong> {{ $attempt->created_at->format('d/m/Y H:i') }}
            </p>
          </div>

          {{-- Foto do usuário à direita --}}
          <img src="{{ $attempt->user->avatar ?: 'https://via.placeholder.com/64' }}" alt="{{ $attempt->user->name }}"
            class="w-16 h-16 rounded-full border-2 border-white shadow-md flex-shrink-0" />
        </div>

        {{-- Conteúdo --}}
        <div class="p-6 space-y-6">
          {{-- Score em Destaque --}}
          <div class="flex justify-center">
            <div class="text-center">
              <div class="text-6xl font-bold flex justify-center items-baseline space-x-2">
                <span @class([ 'text-6xl font-bold' , 'text-red-600 dark:text-red-400'=> $attempt->score <=
                    50, 'text-blue-600 dark:text-blue-400'=> $attempt->score >= 60 && $attempt->score <=
                      80, 'text-green-600 dark:text-green-400'=> $attempt->score >= 90, ])>
                      {{ $attempt->score ?? '—' }}
                </span>
              </div>
              <p class="text-lg font-semibold text-gray-800 dark:text-gray-100 mt-2">
                {{ __('Pontos') }}
              </p>
            </div>
          </div>

          {{-- Detalhes das Perguntas --}}
          <div class="space-y-4">
            @foreach($attempt->quiz->questions as $i => $question)
            @php
            $userAnswer = $attempt->answers->firstWhere('question_id', $question->id);
            $correctId = $question->options->firstWhere('is_correct', true)->id;
            $isCorrect = optional($userAnswer)->option_id === $correctId;
            @endphp

            <div @class([ 'p-4 rounded-lg border-l-4 bg-zinc-50 dark:bg-zinc-900' , 'border-green-500'=> $isCorrect,
              'border-red-500' => ! $isCorrect,
              ])>
              <p class="font-semibold dark:text-gray-100 mb-2">
                {{ $i + 1 }}. {{ $question->text }}
              </p>
              <ul class="space-y-1 ml-4">
                @foreach($question->options as $opt)
                @php
                $selected = optional($userAnswer)->option_id === $opt->id;
                $classes = $opt->is_correct
                ? 'text-green-600 dark:text-green-400 font-medium'
                : ($selected
                ? 'text-red-600 dark:text-red-400 line-through'
                : 'text-gray-700 dark:text-gray-300');
                @endphp
                <li class="flex items-center space-x-2 {{ $classes }}">
                  @if($opt->is_correct)
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  @elseif($selected)
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  @else
                  <span class="inline-block w-5"></span>
                  @endif
                  <span>{{ $opt->text }}</span>
                </li>
                @endforeach
              </ul>
            </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Botão de Voltar --}}
      <div class="text-center">
        <a href="{{ route('quiz.my-quizzes') }}"
          class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-indigo-400">
          {{ __('Voltar aos Quizzes') }}
        </a>
      </div>
    </div>
  </x-slot>
</x-layouts.app.quiz>