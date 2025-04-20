<x-layouts.app :title="$post->title">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="{{ route('posts.index') }}" class="text-accent hover:underline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                返回列表
            </a>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                
                @if(Auth::id() === $post->user_id)
                <div class="flex space-x-2">
                    <a href="{{ route('posts.edit', $post) }}" 
                       class="text-accent hover:underline flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        編輯
                    </a>
                    
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" 
                          onsubmit="return confirm('確定要刪除這篇文章嗎？');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            刪除
                        </button>
                    </form>
                </div>
                @endif
            </div>
            
            <div class="flex items-center mb-4">
                <img src="{{ $post->user->avatarUrl() }}" class="h-10 w-10 rounded-full mr-3" alt="{{ $post->user->name }}">
                <div>
                    <span class="font-semibold">{{ $post->user->name }}</span>
                    <div class="text-zinc-500 dark:text-zinc-400 text-sm">{{ $post->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            
            <!-- 標籤 -->
            @if($post->tags->count() > 0)
            <div class="mb-6 flex flex-wrap">
                @foreach($post->tags as $tag)
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" 
                   class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mr-2 mb-2
                   {{ $tag->color ? 'bg-'.$tag->color.' text-white' : 'bg-zinc-100 dark:bg-zinc-700' }}">
                    {{ $tag->name }}
                </a>
                @endforeach
            </div>
            @endif
            
            <!-- 圖片 -->
            @if($post->image)
            <div class="mb-6">
                <img src="{{ Storage::url($post->image) }}" 
                     alt="{{ $post->title }}" 
                     class="max-w-full h-auto rounded-lg">
            </div>
            @endif
            
            <!-- 內容 -->
            <div class="prose dark:prose-invert max-w-none mb-8">
                {!! nl2br(e($post->content)) !!}
            </div>
            
            <!-- 喜歡按鈕 -->
            <div class="mt-6 flex items-center">
                <form action="{{ route('posts.like', $post) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center {{ $post->likedBy(auth()->user()) ? 'text-red-500' : 'text-zinc-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="{{ $post->likedBy(auth()->user()) ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span>{{ $post->likes()->count() }} 人喜歡</span>
                    </button>
                </form>
            </div>
            
            <!-- 分享按鈕 -->
            <div class="mt-8 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center">
                    <span class="mr-4 text-zinc-600 dark:text-zinc-400">分享這篇文章：</span>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="p-2 bg-blue-400 text-white rounded-full hover:bg-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode('查看這篇文章：' . request()->url()) }}" class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 評論區 -->
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
            <h2 class="text-xl font-bold mb-6">評論 ({{ $post->comments->where('deleted', false)->count() }})</h2>
            
            @auth
            <div class="mb-8">
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-semibold mb-2">發表評論</label>
                        <textarea name="content" id="content" rows="4" 
                                  class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800"
                                  required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                            發表評論
                        </button>
                    </div>
                </form>
            </div>
            @else
            <div class="mb-8 p-4 bg-zinc-100 dark:bg-zinc-700 rounded-md text-center">
                請 <a href="{{ route('login') }}" class="text-accent hover:underline">登入</a> 後發表評論
            </div>
            @endauth
            
            <!-- 評論列表 -->
            <div class="space-y-6">
                @forelse($post->comments->where('deleted', false) as $comment)
                <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4" id="comment-{{ $comment->id }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center mb-2">
                            <img src="{{ $comment->user->avatarUrl(32) }}" alt="{{ $comment->user->name }}" class="h-8 w-8 rounded-full mr-2">
                            <div>
                                <div class="font-semibold">{{ $comment->user->name }}</div>
                                <div class="text-zinc-500 dark:text-zinc-400 text-xs">
                                    {{ $comment->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::id() === $comment->user_id || Auth::id() === $post->user_id)
                        <div class="flex space-x-2">
                            @if(Auth::id() === $comment->user_id)
                            <button onclick="toggleEditForm({{ $comment->id }})" class="text-accent hover:underline text-sm">
                                編輯
                            </button>
                            @endif
                            
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('確定要刪除這則評論嗎？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">
                                    刪除
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <div id="comment-content-{{ $comment->id }}" class="prose dark:prose-invert max-w-none ml-10">
                        {!! nl2br(e($comment->content)) !!}
                    </div>
                    
                    <!-- 編輯評論表單 -->
                    <div id="comment-edit-{{ $comment->id }}" class="hidden mt-2">
                        <form action="{{ route('comments.update', $comment) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-2">
                                <textarea name="content" rows="3" 
                                          class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800"
                                          required>{{ $comment->content }}</textarea>
                            </div>
                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="toggleEditForm({{ $comment->id }})" 
                                        class="px-3 py-1 bg-zinc-200 dark:bg-zinc-700 rounded-md text-sm">
                                    取消
                                </button>
                                <button type="submit" 
                                        class="px-3 py-1 bg-accent text-accent-foreground rounded-md text-sm">
                                    更新
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                    目前沒有評論，成為第一個留言的人吧！
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- 回到頂部按鈕 -->
    <button id="backToTop" class="fixed bottom-8 right-8 p-3 bg-accent text-accent-foreground rounded-full shadow-lg hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>
    
    <script>
        function toggleEditForm(commentId) {
            const contentElement = document.getElementById(`comment-content-${commentId}`);
            const editElement = document.getElementById(`comment-edit-${commentId}`);
            
            if (contentElement.classList.contains('hidden')) {
                contentElement.classList.remove('hidden');
                editElement.classList.add('hidden');
            } else {
                contentElement.classList.add('hidden');
                editElement.classList.remove('hidden');
            }
        }
        
        // 回到頂部功能
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        });
        
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</x-layouts.app>