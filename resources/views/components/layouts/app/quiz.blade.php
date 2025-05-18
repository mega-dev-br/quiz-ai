@props(['title','quiz'])

<x-layouts.app :title="$title">
  <div class="container mx-auto my-8">
    {{-- Você pode exibir algum resumo do $quiz aqui, se quiser --}}
    <header class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
        {{ $quiz->title }}
      </h1>
    </header>

    {{ $slot }}
  </div>
</x-layouts.app>