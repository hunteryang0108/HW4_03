<x-layouts.app title="搜尋結果：{{ $query }}">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">搜尋結果：{{ $query }}</h1>
            
            <!-- 返回首頁連結 -->
            <div class="mb-6">
                <a href="{{ route('posts.index') }}" class="text-accent hover:underline flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    返回留言板
                </a>
            </div>
            
            <!-- 搜尋結果資訊 -->
            <div class="mb-6 bg-white dark:bg-zinc-800 p-4 rounded-lg">
                <p>
                    @if($posts->total() > 0)
                        找到 {{ $posts->total() }} 個與 "<span class="font-semibold">{{ $query }}</span>" 相關的結果
                    @else
                        找不到與 "<span class="font-semibold">{{ $query }}</span>" 相關的結果
                    @endif
                </p>
            </div>
        </div>
        
        <!-- 搜尋結果列表 -->
        <div class="space-y-6">
            @if($posts->isEmpty())
                <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-10 text-center">
                    <p class="text-zinc-500 dark:text-zinc-400 mb-4">沒有找到符合條件的文章</p>
                    <p class="text-zinc-500 dark:text-zinc-400 mb-6">試試其他關鍵字，或瀏覽下方的熱門標籤：</p>
                    
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        @foreach($tags as $tag)
                            <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                {{ $tag->color ? 'bg-'.$tag->color.' text-white' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    
                    <a href="{{ route('posts.index') }}" class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                        瀏覽所有文章
                    </a>
                </div>
            @else
                @foreach($posts as $post)
                    <flux:card>
                        <div class="flex flex-col gap-4 p-6 transition-all hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                            <div class="flex justify-between items-start">
                                <h2 class="text-2xl font-bold">
                                    <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $post->created_at->format('Y-m-d H:i') }}
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
                                {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-800 px-1 rounded">$1</mark>', Str::limit(strip_tags($post->content), 200)) !!}
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-zinc-500 dark:text-zinc-400 text-sm">
                                    {{ $post->comments->count() }} 則評論
                                </div>
                                <a href="{{ route('posts.show', $post) }}" class="text-accent hover:underline">
                                    閱讀更多
                                </a>
                            </div>
                        </div>
                    </flux:card>
                @endforeach

                <!-- 分頁 -->
                <div class="mt-6">
                    {{ $posts->appends(['query' => $query])->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>