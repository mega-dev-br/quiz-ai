<div
  class="w-full max-w-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg p-6">
  <!-- Cabeçalho: título + progresso -->
  <header class="mb-6">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-2">Quiz AI</h1>
    <div class="w-full h-2 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
      <div class="h-2 bg-blue-600 rounded-full" style="width: 20%"></div>
    </div>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pergunta 1 de 5</p>
  </header>

  <!-- Corpo: pergunta e opções -->
  <section class="mb-6">
    <p class="text-lg font-medium text-gray-800 dark:text-gray-100 mb-4">
      Qual é a capital da França?
    </p>
    <div class="space-y-3">
      <label
        class="flex items-center space-x-3 p-3 bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-600">
        <input type="radio" name="answer" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
        <span>Paris</span>
      </label>
      <label
        class="flex items-center space-x-3 p-3 bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-600">
        <input type="radio" name="answer" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
        <span>Londres</span>
      </label>
      <label
        class="flex items-center space-x-3 p-3 bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-600">
        <input type="radio" name="answer" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
        <span>Berlim</span>
      </label>
      <label
        class="flex items-center space-x-3 p-3 bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-600">
        <input type="radio" name="answer" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
        <span>Roma</span>
      </label>
      <label
        class="flex items-center space-x-3 p-3 bg-zinc-50 dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-600">
        <input type="radio" name="answer" class="h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-400" />
        <span>Madri</span>
      </label>
    </div>
  </section>

  <!-- Rodapé: navegação -->
  <footer class="flex justify-between">
    <button
      class="px-4 py-2 bg-zinc-200 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-zinc-300 dark:hover:bg-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
      Anterior
    </button>
    <button
      class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
      Próxima
    </button>
  </footer>
</div>