
<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('歡迎回來') }}, {{ auth()->user()->name }}</flux:heading>
            <flux:subheading>這是你的個人儀表板</flux:subheading>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6 transition-all hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-accent/10 text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <flux:heading size="lg">{{ \App\Models\Post::count() }}</flux:heading>
                <p class="text-zinc-500 dark:text-zinc-400">總貼文數</p>
            </div>
            </flux:card>
            
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6 transition-all hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                    <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-accent/10 text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2m10-4h-4m0 0H9m3 0v4" />
                        </svg>
                    </div>
                    <flux:heading size="lg">{{ \App\Models\Comment::count() }}</flux:heading>
                    <p class="text-zinc-500 dark:text-zinc-400">總留言數</p>
                </div>
            </flux:card>

            
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6 transition-all hover:bg-zinc-50 dark:hover:bg-zinc-700/50">
                    <div class="flex items-center justify-center w-12 h-12 mb-2 rounded-full bg-accent/10 text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M4 20h5v-2a4 4 0 00-3-3.87M15 11a4 4 0 10-6 0 4 4 0 006 0z" />
                        </svg>
                    </div>
                    <flux:heading size="lg">{{ \App\Models\User::count() }}</flux:heading>
                    <p class="text-zinc-500 dark:text-zinc-400">目前總使用者數</p>
                </div>
            </flux:card>

        </div>

        <flux:card>
            <flux:heading size="lg" class="mb-4">最近的貼文</flux:heading>
            @php
                $recentPosts = \App\Models\Post::with('user')
                    ->where('deleted', false)
                    ->latest()
                    ->take(5)
                    ->get();
            @endphp

            @if($recentPosts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPosts as $post)
                        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
                            <div>
                                <flux:link href="{{ route('posts.show', $post) }}" class="font-semibold hover:underline">
                                    {{ $post->title }}
                                </flux:link>
                                <div class="text-sm text-zinc-500">
                                    {{ $post->user->name }} · {{ $post->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                            <div class="text-sm text-zinc-500">
                                {{ $post->comments->count() }} 則回覆
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center py-4 text-zinc-500">目前還沒有任何貼文</p>
            @endif
            
            <div class="mt-4">
                <flux:button href="{{ route('posts.index') }}" wire:navigate>查看所有貼文</flux:button>
            </div>
        </flux:card>
    </div>
</x-layouts.app>