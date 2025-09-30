<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('User詳細') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">

          <div class="relative mb-8">
            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 rounded-lg">
              @if($user->header_image)
              <img src="{{ asset('storage/' . $user->header_image) }}" class="w-full h-full object-cover rounded-lg">
              @endif
            </div>
            <div class="absolute -bottom-8 left-4">
              @if($user->profile_image)
              <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-24 h-24 rounded-full border-4 border-white dark:border-gray-800 object-cover">
              @else
              <div class="w-24 h-24 rounded-full bg-gray-300 border-4 border-white dark:border-gray-800"></div>
              @endif
            </div>
          </div>


         <div class="pt-14">

            <a href="{{ route('tweets.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">一覧に戻る</a>
            <p class="text-gray-800 dark:text-gray-300 text-lg font-bold">{{ $user->name }}</p>

            <p class="mt-2 text-gray-700 dark:text-gray-300">
              {{ $user->bio ?? '自己紹介が設定されていません。' }}
            </p>

            <div class="text-gray-600 dark:text-gray-400 text-sm">
              <p>アカウント作成日時: {{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
            @if ($user->id !== auth()->id())
            <div class="text-gray-900 dark:text-gray-100">
              @if ($user->followers->contains(auth()->id()))
              <form action="{{ route('follow.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">unFollow</button>
              </form>
              @else
              <form action="{{ route('follow.store', $user) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">follow</button>
              </form>
              @endif
            </div>
            @endif

            @if(Auth::id() === $user->id)
            <div class="mt-4">
              <a href="{{ route('profile.edit') }}" class="border border-gray-300 text-gray-700 dark:text-gray-200 font-bold py-2 px-4 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                プロフィールを編集
              </a>
            </div>
            @endif


            <p>following: {{$user->follows->count()}}</p>
            <p>followers: {{$user->followers->count()}}</p>
          
          </div>


          @if ($tweets->count())

          <div class="mb-4">
            {{ $tweets->appends(request()->input())->links() }}
          </div>

          @foreach ($tweets as $tweet)
            <div class="mb-4 p-4 rounded-lg {{ $tweet->pinned_at ? 'bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-300 dark:border-yellow-800' : 'bg-gray-100 dark:bg-gray-700' }}">

              @if ($tweet->pinned_at)
              <div class="flex items-center text-sm text-gray-600 dark:text-yellow-400 mb-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v5h1.6v-5H18v-2l-2-2z"></path></svg>
                <span>ピン留めされたツイート</span>
              </div>
              @endif

              {{-- ツイート本文 --}}
              <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>

              <a href="{{ route('profile.show', $tweet->user) }}">
                <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
              </a>

              <div class="flex items-center justify-between mt-3">
                {{-- 左側の要素（詳細を見る、いいね） --}}
                <div class="flex items-center space-x-4">
                  <a href="{{ route('tweets.show', $tweet) }}" class="text-sm text-blue-500 hover:text-blue-700">詳細を見る</a>

                  @if ($tweet->liked->contains(auth()->id()))
                  <form action="{{ route('tweets.dislike', $tweet) }}" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">dislike {{ $tweet->liked->count() }}</button>
                  </form>
                  @else
                  <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-blue-700">like {{ $tweet->liked->count() }}</button>
                  </form>
                  @endif
                </div>

                @if (auth()->id() == $tweet->user_id)
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
                @endif
              </div>
            </div>
          @endforeach

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