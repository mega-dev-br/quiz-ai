<x-layouts.app.quiz :title="$quiz->title" :quiz="$quiz" {{-- Passa $quiz também para o layout --}}>
  {{-- Aqui apenas invocamos o Livewire, que recebe $quiz novamente --}}
  <livewire:quiz-show :quiz="$quiz" />
</x-layouts.app.quiz>