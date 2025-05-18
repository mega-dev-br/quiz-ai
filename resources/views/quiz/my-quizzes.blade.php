<x-layouts.app :title="__('Novo Quiz')">

  <div class="mx-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-6 mb-6">
    <form action="{{ route('quiz.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- coluna do input file --}}
        <div class="flex justify-center items-center">
          <input id="pdf" name="pdf" type="file" accept="application/pdf" class="block w-full text-sm text-gray-500
                     file:mr-4 file:py-2 file:px-4
                     file:rounded-full file:border-0
                     file:bg-blue-600 file:text-white
                     file:font-semibold
                     hover:file:bg-blue-500
                     focus:outline-none focus:ring-2 focus:ring-blue-400" />
          @error('pdf')
          <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
          @enderror
        </div>

        {{-- coluna do botão, ocupa todo o espaço --}}
        <div class="flex">
          <button type="submit" class="w-full h-48 flex items-center justify-center
                     bg-blue-600 hover:bg-blue-500 text-white font-medium
                     rounded-lg shadow-sm text-lg
                     focus:outline-none focus:ring-2 focus:ring-blue-400">
            {{ __('Enviar e Gerar Quiz') }}
          </button>
        </div>
      </div>
    </form>
  </div>

  <div
    class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-4 overflow-auto">
    <table class="w-full table-auto text-center text-sm">
      <thead class="bg-zinc-100 dark:bg-zinc-800">
        <tr>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Quiz
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Titulo
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Criado em
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Ações
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">

        @foreach ($quizzes as $item)
        <tr class="even:bg-zinc-100 dark:even:bg-zinc-800">
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">
            {{ $item->id }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">
            {{ $item->title }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">
            {{ $item->created_at->format('Y-m-d') }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100 gap-2 flex justify-center">
            <a href="{{ route('quiz.show', $item) }}" class="text-emerald-600 hover:underline">
              Visualizar
            </a>
            <a href="{{ route('quiz.edit', $item) }}" class="text-blue-600 hover:underline">
              Editar
            </a>
            <form action="{{ route('quiz.destroy', $item) }}" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:underline">
                Excluir
              </button>
            </form>
          </td>
        </tr>
        @endforeach
        @if ($quizzes->isEmpty())
        <tr>
          <td colspan="3" class="px-4 py-2 text-zinc-900 dark:text-zinc-100">Nenhum quiz encontrado.</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>

</x-layouts.app>