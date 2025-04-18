
<?php

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.app')] class extends Component {
    use WithPagination;

    public $category = '';

    public function with(): array
    {
        $query = Post::where('deleted', false)
            ->with('user')
            ->orderBy('created_at', 'desc');
            
        if ($this->category) {
            $query->where('category', $this->category);
        }
        
        return [
            'posts' => $query->paginate(10),
            'categories' => Post::where('deleted', false)
                ->select('category')
                ->distinct()
                ->pluck('category')
        ];
    }
}; ?>

<div>
    <div class="mb-6">
        <flux:heading size="xl">留言板</flux:heading>
        <flux:subheading>分享你的想法與經驗</flux:subheading>
    </div>

    <div class="flex justify-between mb-6">
        <div class="flex gap-2">
            <flux:button variant="{{ $category === '' ? 'primary' : 'outline' }}" wire:click="$set('category', '')">
                全部
            </flux:button>
            
            @foreach($categories as $cat)
                <flux:button variant="{{ $category === $cat ? 'primary' : 'outline' }}" wire:click="$set('category', '{{ $cat }}')">
                    {{ $cat }}
                </flux:button>
            @endforeach
        </div>
        
        <flux:button variant="primary" href="{{ route('post.create') }}" wire:navigate>
            新增貼文
        </flux:button>
    </div>

    <div class="grid gap-4">
        @forelse($posts as $post)
            <flux:card>
                <div class="flex justify-between">
                    <flux:heading size="lg">{{ $post->title }}</flux:heading>
                    <span class="text-sm text-zinc-500">{{ $post->created_at->format('Y-m-d H:i') }}</span>
                </div>
                
                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    <span>{{ $post->user->name }}</span>
                    <span class="mx-2">·</span>
                    <span>{{ $post->category }}</span>
                </div>
                
                <p class="mb-2">{{ \Illuminate\Support\Str::limit($post->content, 150) }}</p>
                
                <div class="flex justify-between">
                    <flux:link href="{{ route('post.show', $post) }}" wire:navigate>
                        查看更多
                    </flux:link>
                    <span class="text-sm text-zinc-500">{{ $post->comments()->count() }} 則回覆</span>
                </div>
            </flux:card>
        @empty
            <div class="text-center py-8">
                <p class="text-zinc-600 dark:text-zinc-400">暫無貼文</p>
                <flux:button href="{{ route('post.create') }}" wire:navigate class="mt-4">
                    立即發佈第一則貼文
                </flux:button>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>