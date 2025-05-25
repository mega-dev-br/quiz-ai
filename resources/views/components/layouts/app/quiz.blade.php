<!DOCTYPE html>
<html lang="pt_BR">

<head>
  @include('partials.head')
</head>

<body
  class="min-h-screen bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 flex items-center justify-center p-4">

  {{ $content }}

  @stack('scripts')

  @livewireScripts
</body>

</html>