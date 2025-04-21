<x-layouts.app title="留言板">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">留言板</h1>

            <!-- 搜尋欄 -->
            <x-search-bar />



            
            <!-- 標籤過濾區域 -->
            <div class="mb-6 py-2 overflow-x-auto scrollbar-thin scrollbar-thumb-zinc-200 dark:scrollbar-thumb-zinc-700">
                <div class="inline-flex space-x-2">
                    <a href="{{ route('posts.index') }}" 
                    class="inline-block px-4 py-2 rounded-full border transition-colors
                    {{ !$currentTag ? 'bg-accent text-accent-foreground border-accent' : 'bg-transparent border-zinc-200 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        全部文章
                    </a>
                    
                    @foreach($tags as $tag)
                    <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                    class="flex items-center justify-center px-4 py-2 rounded-full border transition-colors text-center
                    {{ $currentTag == $tag->slug 
                        ? ($tag->color ? 'bg-'.$tag->color.' text-white border-'.$tag->color : 'bg-accent text-accent-foreground border-accent') 
                        : 'bg-transparent border-zinc-200 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800' }}">
                        {{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'latest'])) }}" 
                       class="px-3 py-2 rounded-md text-sm {{ request('sort', 'latest') == 'latest' ? 'bg-accent text-accent-foreground' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                        最新
                    </a>
                    <a href="{{ route('posts.index', array_merge(request()->except('sort'), ['sort' => 'commented'])) }}" 
                       class="px-3 py-2 rounded-md text-sm {{ request('sort') == 'commented' ? 'bg-accent text-accent-foreground' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                        熱門討論
                    </a>
                </div>
                
                <a href="{{ route('posts.create') }}" 
                   class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                    發佈新文章
                </a>
            </div>
        </div>
        
        <!-- 文章列表 -->
        <div class="space-y-6">
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
                            {{ Str::limit(strip_tags($post->content), 200) }}
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
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>