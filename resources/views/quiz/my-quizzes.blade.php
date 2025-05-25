<x-layouts.app :title="__('Meus Quizzes')">
  <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-6 mb-6">
    <h1 class="
      flex items-center mb-6
      text-3xl font-extrabold
      before:block before:w-1 before:h-full before:mr-3 before:rounded
        before:bg-gradient-to-b before:from-green-500 before:to-gray-500
      text-transparent bg-clip-text
      bg-gradient-to-r from-green-400 via-teal-400 to-gray-400
      dark:drop-shadow-[0_0_10px_rgba(0,200,0,0.6)]
    ">
      <svg class="w-6 h-6 mr-2 shrink-0 fill-current text-green-600 dark:text-gray-300"
        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M19 11H5m7-7v14" />
      </svg>

      {{ __('Novo quiz') }}
    </h1>

    <form action="{{ route('quiz.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-6">
          <div>
            <input id="title" name="title" type="text" placeholder="{{ __('Digite o título…') }}"
              class="block w-full text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-slate-400"
              required />
            @error('title')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <select id="category" name="category"
                class="block w-full text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-slate-400"
                required>
                <option value="">{{ __('Categoria...') }}</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->description }}</option>
                @endforeach
              </select>
              @error('category')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <select id="qtd" name="qtd"
                class="block w-full text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-slate-400"
                required>

                <option value="">Quantidade de questões...</option>
                @foreach (range(5, 100) as $qtd)
                <option value="{{ $qtd }}">{{ $qtd }}</option>
                @endforeach

              </select>
              @error('qtd')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <input id="chapters" name="chapters" type="text" placeholder="{{ __('Capitulo (opcional)...') }}"
                class="block w-full text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-slate-400" />
              @error('chapters')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <select id="difficulty" name="difficulty"
                class="block w-full text-sm text-gray-900 dark:text-gray-200 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-400 dark:focus:ring-slate-400"
                required>
                <option value="">{{ __('Dificuldade...') }}</option>
                @foreach ($difficulties as $difficulty)
                <option value="{{ $difficulty->id }}">{{ $difficulty->description }}</option>
                @endforeach
              </select>
              @error('difficulty')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="space-y-6">
            <div>
              <div class="flex items-center gap-4">
                <label for="pdf" class="flex items-center gap-2 px-4 py-2
                bg-gradient-to-r from-gray-100 to-gray-200
                dark:from-gray-800 dark:to-gray-700
                border border-gray-300 dark:border-gray-600
                text-gray-900 dark:text-white
                rounded-lg cursor-pointer
                hover:from-gray-200 hover:to-gray-300
                dark:hover:from-gray-700 dark:hover:to-gray-600
                transition-all duration-200
                w-full justify-center">
                  <p id="file-name" class="font-medium text-sm text-center w-full">
                    Selecionar PDF
                  </p>
                </label>

                <input id="pdf" name="pdf" type="file" accept="application/pdf" class="hidden"
                  onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Selecionar PDF'"
                  required />
              </div>
              @error('pdf')
              <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>
      </div>


      <div class="flex items-start justify-center">
        <button type="submit" class="flex items-center gap-2 px-4 py-2
        bg-gradient-to-r from-gray-100 to-gray-200
        dark:from-gray-800 dark:to-gray-700
        border border-gray-300 dark:border-gray-600
        text-gray-900 dark:text-white
        rounded-lg cursor-pointer
        hover:from-gray-200 hover:to-gray-300
        dark:hover:from-gray-700 dark:hover:to-gray-600
        transition-all duration-200
        w-full justify-center">
          {{-- Ícone (opcional) --}}
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7" />
          </svg>

          <span>{{ __('Enviar e Gerar Quiz') }}</span>
        </button>
      </div>

    </form>
  </div>

  <div class="flex flex-col md:flex-row md:gap-6">
    <div
      class="w-full md:w-1/2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-6 mb-6 overflow-x-auto">
      <h1 class="
        flex items-center mb-6
        text-3xl font-extrabold
        before:block before:w-1 before:h-full before:mr-3 before:rounded
          before:bg-gradient-to-b before:from-blue-500 before:to-gray-500
        text-transparent bg-clip-text
        bg-gradient-to-r from-blue-400 via-cyan-400 to-gray-400
        dark:drop-shadow-[0_0_10px_rgba(100,180,255,0.6)]
      ">
        <svg class="w-6 h-6 mr-2 shrink-0 fill-current text-blue-600 dark:text-gray-300"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M19 11H5m7-7v14" />
        </svg>

        {{ __('Meus quizzes') }}
      </h1>

      <div
        class="overflow-x-auto rounded-xl shadow-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700">
        <div
          class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 shadow">

          <table class="w-full table-auto text-sm">
            <thead class="sticky top-0 bg-white/70 dark:bg-zinc-800/70">
              <tr>
                <th
                  class="w-3/4 px-4 py-3 text-zinc-700 dark:text-zinc-300 font-semibold uppercase text-start bg-white dark:bg-zinc-800 first:rounded-tl-xl last:rounded-tr-xl">
                  Quiz
                </th>
                <th
                  class="w-1/4 px-4 py-3 text-zinc-700 dark:text-zinc-300 font-semibold uppercase text-center bg-white dark:bg-zinc-800 first:rounded-tl-xl last:rounded-tr-xl">
                  Controles
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
              @forelse ($quizzes as $item)
              <tr
                class="even:bg-zinc-50 dark:even:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors duration-150">
                <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">
                  {{ $item->title }}
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="inline-flex items-center justify-center gap-2">
                    {{-- Copiar link --}}
                    <flux:tooltip content="Copiar link do quiz">
                      <flux:link onclick="copyQuizLink('{{ route('quiz.show', $item) }}')"
                        class="p-2 text-emerald-500 hover:text-emerald-700 rounded-md focus:outline-none cursor-pointer">
                        <flux:icon.clipboard class="w-5 h-5 cursor-pointer" />
                      </flux:link>
                    </flux:tooltip>

                    {{-- Realizar quiz --}}
                    <flux:tooltip content="Realizar o quiz">
                      <flux:link href="{{ route('quiz.show', $item) }}" target="_blank"
                        class="p-2 text-blue-500 hover:text-blue-700 rounded-md focus:outline-none cursor-pointer">
                        <flux:icon.play-pause class="w-5 h-5" />
                      </flux:link>
                    </flux:tooltip>

                    {{-- exclui o quiz --}}
                    <form id="delete-quiz-{{ $item->id }}" action="{{ route('quiz.destroy', $item) }}" method="POST"
                      class="hidden">
                      @csrf
                      @method('DELETE')
                    </form>

                    <flux:tooltip content="Excluir o quiz">
                      <flux:link
                        class="p-2 text-red-500 hover:text-red-700 rounded-md focus:outline-none cursor-pointer"
                        onclick="document.getElementById('delete-quiz-{{ $item->id }}').submit()">
                        <flux:icon.trash class="w-5 h-5" />
                      </flux:link>
                    </flux:tooltip>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="2" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">
                  {{ __('Nenhum quiz encontrado.') }}
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div
      class="w-full md:w-1/2 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-6 mb-6 overflow-x-auto">
      <h1 class="
          flex items-center mb-6
          text-3xl font-extrabold
          before:block before:w-1 before:h-full before:mr-3 before:rounded
            before:bg-gradient-to-b before:from-pink-500 before:to-purple-500
          text-transparent bg-clip-text
          bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400
          dark:drop-shadow-[0_0_10px_rgba(255,0,255,0.6)]
        ">
        <svg class="w-6 h-6 mr-2 shrink-0 fill-current text-purple-500 dark:text-indigo-300"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            d="M19 11H5m7-7v14" />
        </svg>

        {{ __('Minhas tentativas') }}
      </h1>

      <div
        class="overflow-x-auto rounded-xl shadow-md bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700">
        <div
          class="rounded-xl overflow-hidden border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 shadow">

          <table class="w-full table-auto text-sm">
            <thead class="sticky top-0 bg-white/70 dark:bg-zinc-800/70">
              <tr>
                <th
                  class="w-3/4 px-4 py-3 text-zinc-700 dark:text-zinc-300 font-semibold uppercase text-start bg-white dark:bg-zinc-800 first:rounded-tl-xl last:rounded-tr-xl">
                  Quiz
                </th>
                <th
                  class="w-1/4 px-4 py-3 text-zinc-700 dark:text-zinc-300 font-semibold uppercase text-center bg-white dark:bg-zinc-800 first:rounded-tl-xl last:rounded-tr-xl">
                  Score
                </th>
                <th
                  class="w-1/4 px-4 py-3 text-zinc-700 dark:text-zinc-300 font-semibold uppercase text-center bg-white dark:bg-zinc-800 first:rounded-tl-xl last:rounded-tr-xl">
                  Ver
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
              @forelse ($attempts as $attempt)
              <tr
                class="even:bg-zinc-50 dark:even:bg-zinc-800 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors duration-150">
                <td class="px-4 py-3 text-zinc-900 dark:text-zinc-100">
                  {{ $attempt->quiz->title }}
                </td>
                <td class="px-4 py-3 text-center">
                  {{ $attempt->score }}
                </td>
                <td class="px-4 py-3 text-center whitespace-nowrap">
                  <div class="inline-flex items-center justify-center gap-2">
                    {{-- Ver o quiz --}}
                    <flux:tooltip content="Ver o quiz">
                      <flux:link href="{{ route('quiz.result', $attempt) }}"
                        class="p-2 text-emerald-500 hover:text-emerald-700 rounded-md focus:outline-none cursor-pointer">
                        <flux:icon.eye class="w-5 h-5" />
                      </flux:link>
                    </flux:tooltip>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">
                  {{ __('Nenhuma tentativa encontrada.') }}
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    function copyQuizLink(url) {
      navigator.clipboard.writeText(url)
        .then(() => {
          // dispara um evento customizado
          window.dispatchEvent(new CustomEvent('flash-message', {
            detail: { type: 'success', message: 'Link copiado!' }
          }));
        })
        .catch(() => {
          window.dispatchEvent(new CustomEvent('flash-message', {
            detail: { type: 'error', message: 'Falha ao copiar.' }
          }));
        });
    }
  
    // captura e exibe o toast — aqui um exemplo minimalista:
    window.addEventListener('flash-message', e => {
      const { type, message } = e.detail;
      // você pode usar sua lib de toasts (ex: Toastify, Notyf) ou um simples alert:
      // alert(message);
  
      // Exemplo bem básico criando um banner:
      const banner = document.createElement('div');
      banner.textContent = message;
      banner.className = [
        'fixed top-4 right-4 px-4 py-2 rounded shadow-md',
        type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
      ].join(' ');
      document.body.appendChild(banner);
      setTimeout(() => banner.remove(), 3000);
    });
  </script>
  @endpush

</x-layouts.app>