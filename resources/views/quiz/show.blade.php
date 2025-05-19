<x-layouts.app.quiz :title="__('Quiz AI')">
  <x-slot name="content">

    <livewire:quiz-show :quiz="$quiz" />

  </x-slot>
</x-layouts.app.quiz>