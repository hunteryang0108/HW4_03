
<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col gap-6">
        <div>
            <flux:heading size="xl">{{ __('歡迎回來') }}, {{ auth()->user()->name }}</flux:heading>
            <flux:subheading>這是你的個人儀表板</flux:subheading>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6">
                    <flux:heading size="lg">{{ \App\Models\Post::count() }}</flux:heading>
                    <p>總貼文數</p>
                </div>
            </flux:card>
            
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6">
                    <flux:heading size="lg">{{ \App\Models\Comment::count() }}</flux:heading>
                    <p>總留言數</p>
                </div>
            </flux:card>
            
            <flux:card>
                <div class="flex flex-col items-center justify-center gap-2 p-6">
                    <flux:heading size="lg">{{ \App\Models\User::count() }}</flux:heading>
                    <p>目前總使用者數</p>
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