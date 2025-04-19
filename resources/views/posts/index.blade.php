<x-layouts.app title="留言板">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">留言板</h1>
            
            <!-- 標籤過濾區域 -->
            <div class="mb-6 overflow-x-auto whitespace-nowrap py-2">
                <a href="{{ route('posts.index') }}" 
                   class="inline-block px-4 py-2 rounded-full mr-2 mb-2 
                   {{ !$currentTag ? 'bg-accent text-accent-foreground' : 'bg-zinc-200 dark:bg-zinc-700' }}">
                    全部文章
                </a>
                
                @foreach($tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                   class="inline-block px-4 py-2 rounded-full mr-2 mb-2 
                   {{ $currentTag == $tag->slug ? 'bg-accent text-accent-foreground' : 'bg-zinc-200 dark:bg-zinc-700' }}
                   {{ $tag->color ? 'border-2 border-'.$tag->color : '' }}">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('posts.create') }}" 
                   class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                    發佈新文章
                </a>
            </div>
        </div>
        
        <!-- 文章列表 -->
        <div class="space-y-6">
            @forelse($posts as $post)
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 bg-white dark:bg-zinc-800">
                    <div class="flex justify-between items-start">
                        <h2 class="text-2xl font-bold mb-2">
                            <a href="{{ route('posts.show', $post) }}" class="hover:underline">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $post->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    
                    <div class="flex items-center mb-4">
                        <div class="flex items-center text-sm text-zinc-500 dark:text-zinc-400">
                            <span>由 {{ $post->user->name }} 發佈</span>
                        </div>
                    </div>
                    
                    <!-- 標籤 -->
                    @if($post->tags->count() > 0)
                    <div class="mb-4 flex flex-wrap">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                           class="bg-zinc-100 dark:bg-zinc-700 text-sm px-3 py-1 rounded-full mr-2 mb-2">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="prose dark:prose-invert mb-4 line-clamp-3">
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
            @empty
                <div class="text-center py-12 text-zinc-500 dark:text-zinc-400">
                    目前沒有文章，成為第一個發佈文章的人吧！
                </div>
            @endforelse
            
            <!-- 分頁 -->
            <div class="mt-6">
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>