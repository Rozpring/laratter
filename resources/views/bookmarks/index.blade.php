<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('ブックマーク一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          @forelse ($tweets as $tweet)
            <div class="mb-4 p-4 rounded-lg {{ $tweet->pinned_at ? 'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-800' : 'bg-gray-100 dark:bg-gray-700' }}">

              @if ($tweet->pinned_at)
              <div class="flex items-center text-sm text-gray-600 dark:text-yellow-400 mb-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v5h1.6v-5H18v-2l-2-2z"></path></svg>
                <span>ピン留めされたツイート</span>
              </div>
              @endif

              <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
              <a href="{{ route('profile.show', $tweet->user) }}">
                <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
              </a>

              <div class="flex items-center justify-between mt-3">
                <div class="flex items-center space-x-4">
                  @if ($tweet->liked->contains(auth()->id()))
                    <form action="{{ route('tweets.dislike', $tweet) }}" method="POST" class="m-0">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="flex items-center text-sm text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" /></svg>
                        <span>dislike {{ $tweet->liked->count() }}</span>
                      </button>
                    </form>
                  @else
                    <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="m-0">
                      @csrf
                      <button type="submit" class="flex items-center text-sm text-gray-500 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        <span>like {{ $tweet->liked->count() }}</span>
                      </button>
                    </form>
                  @endif
                  <a href="{{ route('tweets.show', $tweet) }}" class="text-sm text-gray-500 hover:text-blue-700">詳細を見る</a>
                
                  @auth
                    @if(Auth::user()->bookmark_tweets->contains($tweet->id))
                      <form action="{{ route('tweets.unbookmark', $tweet) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center text-sm text-blue-500 hover:text-blue-700">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-3.13L5 18V4z" />
                          </svg>
                        </button>
                      </form>
                    @else
                      <form action="{{ route('tweets.bookmark', $tweet) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="flex items-center text-sm text-gray-500 hover:text-blue-700">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                          </svg>
                        </button>
                      </form>
                    @endif
                  @endauth
                
                </div>

                @can('pin', $tweet)
                  <div>
                    @if ($tweet->pinned_at)
                      <form action="{{ route('tweets.unpin', $tweet) }}" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200 font-semibold">ピン留めを外す</button>
                      </form>
                    @else
                      <form action="{{ route('tweets.pin', $tweet) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-semibold">ピン留めする</button>
                      </form>
                    @endif
                  </div>
                @endcan
              </div>
            </div>
          @empty
            <p class="text-center text-gray-500">ブックマークしたツイートはまだありません。</p>
          @endforelse
          <div class="mt-6">
            {{ $tweets->links() }}
          </div>

        </div>
      </div>
    </div>
  </div>
</x-app-layout>
