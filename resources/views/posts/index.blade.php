
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
    </div>
</x-layouts.app>