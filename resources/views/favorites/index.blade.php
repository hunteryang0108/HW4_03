<x-layouts.app title="我的收藏">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">我的收藏</h1>
            
            <div class="mb-4">
                <a href="{{ route('profile.show', auth()->id()) }}" class="text-accent hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    返回個人檔案
                </a>
            </div>
        </div>
        
        <!-- 收藏文章列表 -->
        <div class="space-y-6">
            @if($favoritePosts->isEmpty())
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-10 text-center">
                    <p class="text-zinc-500 dark:text-zinc-400 mb-4">您尚未收藏任何文章</p>
                    <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                        瀏覽文章
                    </a>
                </div>
            @else
                @foreach($favoritePosts as $post)
                    <flux:card>
                        <div class="flex flex-col gap-4 p-6 transition-all hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-bold">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    收藏於 {{ $post->pivot->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <img src="{{ $post->user->avatarUrl(32) }}" class="h-8 w-8 rounded-full mr-2" alt="{{ $post->user->name }}">
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    由 {{ $post->user->name }} 發佈
                                </div>
                            </div>

                            @if($post->tags->count() > 0)
                                <div class="flex flex-wrap">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                                           class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mr-2 mb-2
                                           {{ $tag->color ? 'bg-'.$tag->color.' text-white' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="prose dark:prose-invert line-clamp-3">
                                {{ Str::limit(strip_tags($post->content), 200) }}
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-zinc-500 dark:text-zinc-400 text-sm">
                                    {{ $post->comments->count() }} 則評論
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('posts.show', $post) }}" class="text-accent hover:underline">
                                        閱讀更多
                                    </a>
                                    <form action="{{ route('posts.favorite', $post) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-yellow-500 hover:underline">
                                            取消收藏
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                @endforeach

                <!-- 分頁 -->
                <div class="mt-6">
                    {{ $favoritePosts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>