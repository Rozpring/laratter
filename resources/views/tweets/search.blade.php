<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tweet検索') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <!-- 検索フォーム -->
          <form action="{{ route('tweets.search') }}" method="GET" class="mb-6">
            <div class="flex items-center">
              <input type="text" name="keyword" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Search for tweets..." value="{{ request('keyword') }}">
              <button type="submit" class="ml-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">
                Search
              </button>
            </div>
          </form>

          <!-- 検索結果表示 -->
          @if ($tweets->count())

          @foreach ($tweets as $tweet)
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>

            <div class="flex items-center mt-4 text-sm">
              
              <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>

              <div class="ml-4">
                @if ($tweet->liked->contains(auth()->id()))
                <form action="{{ route('tweets.dislike', $tweet) }}" method="POST" class="m-0">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="flex items-center text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                    <span>{{ $tweet->liked->count() }}</span>
                  </button>
                </form>
                @else
                <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="m-0">
                  @csrf
                  <button type="submit" class="flex items-center text-gray-500 hover:text-blue-700">
                    <svg xmlns="http://www.w.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    <span>{{ $tweet->liked->count() }}</span>
                  </button>
                </form>
                @endif
              </div>

              <div class="ml-4">
                @auth
                  @if(Auth::user()->bookmark_tweets->contains($tweet->id))
                    <form action="{{ route('tweets.unbookmark', $tweet) }}" method="POST" class="m-0">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="flex items-center text-blue-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-3.13L5 18V4z" /></svg>
                      </button>
                    </form>
                  @else
                    <form action="{{ route('tweets.bookmark', $tweet) }}" method="POST" class="m-0">
                      @csrf
                      <button type="submit" class="flex items-center text-gray-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                      </button>
                    </form>
                  @endif
                @endauth
              </div>

            </div>
            </div>
          @endforeach

          <!-- ページネーション -->
          <div class="mt-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>

          @else
          <p>No tweets found.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
