<x-layouts.app :title="__('Arquivos na Open AI')">

  <div
    class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow p-6 mb-6 overflow-x-auto">
    {{-- Cabeçalho --}}
    <h1 class="flex items-center text-2xl font-extrabold mb-6 p-4 rounded-lg shadow-lg
               text-gray-900 dark:text-white
               bg-gradient-to-r from-zinc-100 to-blue-300
               dark:from-zinc-700 dark:to-slate-900">
      {{-- ícone alinhado à esquerda --}}
      <svg class="w-6 h-6 mr-2 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m7-7v14" />
      </svg>

      {{ __('Meus arquivos na Open AI') }}
    </h1>

    {{-- Tabela --}}
    <table class="w-full table-auto text-sm">
      <thead class="bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm">
        <tr>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase text-start">
            ID
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase text-start">
            Nome do Arquivo
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Tamanho
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Criado em
          </th>
          <th class="px-4 py-2 font-semibold text-zinc-700 dark:text-zinc-300 uppercase">
            Controles
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
        @forelse ($files['data'] as $file)
        <tr class="even:bg-zinc-100 dark:even:bg-zinc-800">
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">
            {{ $file['id'] }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100">
            {{ $file['filename'] }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100 text-center">
            {{ formatBytes($file['bytes'], 3) }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100 text-center">
            {{ formatDate($file['created_at']) }}
          </td>
          <td class="px-4 py-2 text-zinc-900 dark:text-zinc-100 flex items-center justify-center gap-3">
            <form action="{{ route('open-ai.destroy', $file['id']) }}" method="POST" class="inline">
              @csrf @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800">
                {{ __('Excluir') }}
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-4 py-6 text-center text-zinc-500 dark:text-zinc-400">
            {{ __('Nenhum quiz encontrado.') }}
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</x-layouts.app>