<x-layouts.app :title="$post->title">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-4">
            <flux:button href="{{ route('posts.index') }}" variant="outline">
                <flux:icon.arrow-left class="mr-2" /> 返回列表
            </flux:button>
        </div>

        @if(session('success'))
            <flux:alert variant="success" class="mb-4">
                {{ session('success') }}
            </flux:alert>
        @endif

        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <flux:heading size="xl">{{ $post->title }}</flux:heading>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                            分類：{{ $post->category }} | 發布於：{{ $post->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                    
                    @if(Auth::id() === $post->user_id)
                        <div class="flex space-x-2">
                            <flux:button href="{{ route('posts.edit', $post) }}" variant="outline">
                                <flux:icon.pencil-square /> 編輯
                            </flux:button>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('確定要刪除此文章嗎？');">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="danger">
                                    <flux:icon.trash /> 刪除
                                </flux:button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="flex items-center mb-6">
                    <flux:profile 
                        class="mr-2"
                        :initials="$post->user->initials()" 
                    />
                    <span>{{ $post->user->name }}</span>
                </div>

                @if($post->image)
                    <div class="mb-6">
                        <img src="{{ route('posts.image', $post) }}" 
                            alt="{{ $post->title }}" 
                            class="w-full max-h-96 object-contain">
                    </div>
                @endif

                <div class="prose dark:prose-invert max-w-none">
                    {{ $post->content }}
                </div>
            </div>
        </div>

        <!-- 留言區 -->
        <flux:heading size="lg" class="mb-6">留言（{{ $comments->total() }}）</flux:heading>

        @auth
            <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6 mb-8">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <flux:textarea 
                            name="content" 
                            rows="3" 
                            placeholder="發表您的留言..."
                            required
                        >{{ old('content') }}</flux:textarea>
                    </div>
                    <div class="flex justify-end">
                        <flux:button type="submit" variant="primary">
                            發表留言
                        </flux:button>
                    </div>
                </form>
            </div>
        @else
            <flux:alert variant="info" class="mb-8">
                請 <a href="{{ route('login') }}" class="underline">登入</a> 後發表留言
            </flux:alert>
        @endauth

        @if($comments->isEmpty())
            <flux:alert variant="info">
                目前沒有留言，成為第一個留言的人吧！
            </flux:alert>
        @else
            <div class="space-y-6">
                @foreach($comments as $comment)
                    <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6" id="comment-{{ $comment->id }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center">
                                <flux:profile 
                                    class="mr-2"
                                    :initials="$comment->user->initials()" 
                                />
                                <div>
                                    <div class="font-semibold">{{ $comment->user->name }}</div>
                                    <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                        {{ $comment->created_at->format('Y-m-d H:i') }}
                                    </div>
                                </div>
                            </div>
                            
                            @if(Auth::id() === $comment->user_id)
                                <div class="flex space-x-2">
                                    <flux:button href="{{ route('comments.edit', $comment) }}" variant="outline" size="sm">
                                        <flux:icon.pencil-square /> 編輯
                                    </flux:button>
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('確定要刪除此留言嗎？');">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger" size="sm">
                                            <flux:icon.trash /> 刪除
                                        </flux:button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            {{ $comment->content }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>