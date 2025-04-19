<<<<<<< HEAD

<x-layouts.app :title="__('留言板')">
    <div class="flex flex-col gap-6">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl">{{ __('留言板') }}</flux:heading>
                <flux:subheading>分享你的想法與經驗</flux:subheading>
            </div>
            
            <flux:button variant="primary" href="{{ route('posts.create') }}">
                <flux:icon.plus class="w-4 h-4 mr-2" /> 新增文章
            </flux:button>
        </div>
        
        @if(isset($categories) && count($categories) > 0)
            <div class="flex flex-wrap gap-2">
                <flux:button variant="{{ !request('category') ? 'primary' : 'outline' }}" href="{{ route('posts.index') }}">
                    全部
                </flux:button>
                
                @foreach($categories as $cat)
                    <flux:button variant="{{ request('category') == $cat ? 'primary' : 'outline' }}" href="{{ route('posts.index', ['category' => $cat]) }}">
                        {{ $cat }}
                    </flux:button>
                @endforeach
            </div>
        @endif
        
        @if(isset($posts) && $posts->count() > 0)
            <div class="grid gap-4">
                @foreach($posts as $post)
                    <flux:card class="transition hover:shadow-md">
                        <div class="flex justify-between">
                            <flux:heading size="lg">{{ $post->title }}</flux:heading>
                            <span class="text-sm text-zinc-500">{{ $post->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                            <flux:icon.user class="w-4 h-4 mr-1" />
                            <span>{{ $post->user->name }}</span>
                            @if($post->category)
                                <span class="mx-2">·</span>
                                <flux:icon.tag class="w-4 h-4 mr-1" />
                                <span>{{ $post->category }}</span>
                            @endif
                        </div>
                        
                        <p class="mb-4">{{ \Illuminate\Support\Str::limit($post->content, 150) }}</p>
                        
                        <div class="flex justify-between items-center">
                            <flux:button variant="outline" href="{{ route('posts.show', $post) }}">
                                閱讀更多
                            </flux:button>
                            
                            <div class="flex items-center">
                                <flux:icon.chat-bubble-left-ellipsis class="w-5 h-5 mr-1 text-zinc-500" />
                                <span class="text-zinc-500">{{ $post->comments->count() }} 則回覆</span>
                            </div>
                        </div>
                    </flux:card>
                @endforeach
                
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        @else
            <flux:card class="text-center py-10">
                <flux:icon.document-text class="w-16 h-16 mx-auto mb-4 text-zinc-300 dark:text-zinc-600" />
                <flux:heading size="lg" class="mb-2">目前沒有任何文章</flux:heading>
                <p class="text-zinc-500 mb-6">成為第一個發表文章的人吧！</p>
                <flux:button variant="primary" href="{{ route('posts.create') }}">
                    <flux:icon.plus class="w-4 h-4 mr-2" /> 新增文章
                </flux:button>
            </flux:card>
        @endif
=======
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
                    {{ $tag->name }} ({{ $tag->posts_count }})
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
>>>>>>> 0608ffd527b24f5bdeeb4aec3406f9fe1d9ab465
    </div>
</x-layouts.app>