<x-layouts.app :title="__('Dashboard')">

  <form action="{{ route('quiz.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div>
      <label for="pdf">Upload PDF:</label>
      <flux:input.file name="pdf" multiple class="mt-4" />
    </div>

    <flux:button type="submit" variant="primary" class="mt-4">
      {{ __('Upload') }}
    </flux:button>
  </form>

</x-layouts.app>