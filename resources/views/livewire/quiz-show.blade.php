<div class="min-h-screen flex items-center justify-center p-4 bg-white dark:bg-zinc-900">
  <div
    class="w-full md:w-1/3 md:fixed bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg p-6">
    {{-- Cabeçalho --}}
    <header class="mb-6">
      <h1 class="w-full text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
        {{ $quiz->title }}
      </h1>
      <div class="w-full h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
        <div class="h-2 bg-blue-600 rounded-full transition-all duration-300"
          style="width: {{ intval(($currentIndex + 1) / count($questions) * 100) }}%;"></div>
      </div>
      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
        Pergunta {{ $currentIndex + 1 }} de {{ count($questions) }}
      </p>
    </header>

    {{-- Pergunta e opções --}}
    @php($q = $questions[$currentIndex])
    <section wire:key="question-{{ $currentIndex }}" class="mb-6">
      <p class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">
        {{ $q['text'] }}
      </p>
      <div class="space-y-3">
        @foreach($q['options'] as $opt)
        <label class="flex items-center space-x-3 p-3
                   bg-zinc-50 dark:bg-zinc-700
                   border dark:border-zinc-600
                   rounded-lg cursor-pointer
                   hover:bg-zinc-100 dark:hover:bg-zinc-600
                   {{ (data_get($answers, $currentIndex) === $opt['id'])
                       ? 'border-blue-600 bg-blue-50 dark:bg-blue-900'
                       : '' }}">
          <input type="radio" wire:model="answers.{{ $currentIndex }}" value="{{ $opt['id'] }}"
            class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
          <span class="text-gray-800 dark:text-gray-100">{{ $opt['text'] }}</span>
        </label>
        @endforeach
      </div>
    </section>

    {{-- Navegação --}}
    <footer class="flex justify-between">
      <button wire:click="previous" @disabled($currentIndex===0)
        class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-zinc-300 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-400 disabled:opacity-50">
        Anterior
      </button>

      @if($currentIndex + 1 < count($questions)) <button wire:click="next"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
        Próxima
        </button>
        @else
        <button wire:click="finish"
          class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-400">
          Finalizar
        </button>
        @endif
    </footer>
  </div>
</div>