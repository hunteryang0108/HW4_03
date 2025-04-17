<x-layouts.app :title="__('所有文章')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <flux:heading size="xl">所有文章</flux:heading>
            @auth
                <flux:button href="{{ route('posts.create') }}" variant="primary">
                    <flux:icon.plus-circle class="mr-2" /> 新增文章
                </flux:button>
            @endauth
        </div>

        @if(session('success'))
            <flux:alert variant="success" class="mb-4">
                {{ session('success') }}
            </flux:alert>
        @endif

        @if($posts->isEmpty())
            <flux:alert variant="info">
                目前沒有任何文章，成為第一個發表文章的人吧！
            </flux:alert>
        @else
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <flux:card class="h-full">
                        <flux:card.header>
                            <flux:heading size="md">{{ $post->title }}</flux:heading>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $post->category }} | {{ $post->created_at->diffForHumans() }}
                            </div>
                        </flux:card.header>
                        
                        @if($post->image)
                            <img src="{{ route('posts.image', $post) }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-48 object-cover">
                        @endif
                        
                        <flux:card.body>
                            <p class="mb-4">{{ Str::limit($post->content, 150) }}</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <flux:profile 
                                        class="mr-2"
                                        size="sm"
                                        :initials="$post->user->initials()" 
                                    />
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <flux:button href="{{ route('posts.show', $post) }}" variant="link">
                                    閱讀更多 →
                                </flux:button>
                            </div>
                        </flux:card.body>
                    </flux:card>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>