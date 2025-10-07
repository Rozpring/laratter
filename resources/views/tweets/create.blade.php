<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tweet作成') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          
          <form method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
              <label for="tweet" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tweet</label>
              <input type="text" name="tweet" id="tweet" value="{{ old('tweet') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              @error('tweet')
              <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-4">
              <label for="image" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">画像 (任意)</label>
              <input type="file" name="image" id="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
              @error('image')
              <span class="text-red-500 text-xs italic">{{ $message }}</span>
              @enderror
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Tweet</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>