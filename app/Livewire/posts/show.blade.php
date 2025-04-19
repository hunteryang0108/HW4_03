
<?php

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public Post $post;
    
    #[Rule('required|min:3')]
    public $content = '';
    
    public function mount(Post $post)
    {
        $this->post = $post->load(['user', 'comments.user', 'tags']);
    }
    
    public function addComment()
    {
        $this->validate();
        
        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->content
        ]);
        
        $this->reset('content');
        $this->post = $this->post->fresh(['comments.user']);
    }
}; ?>

<div>
    <div class="mb-4">
        <flux:link href="{{ route('post.index') }}" wire:navigate>
            <flux:icon.arrow-left class="w-4 h-4 inline mr-1" /> 返回列表
        </flux:link>
    </div>

    <flux:card class="mb-6">
        <div class="flex justify-between mb-2">
            <flux:heading size="xl">{{ $post->title }}</flux:heading>
            <span class="text-sm text-zinc-500">{{ $post->created_at->format('Y-m-d H:i') }}</span>
        </div>
        
        <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400 mb-4">
            <span>{{ $post->user->name }}</span>
            <span class="mx-2">·</span>
            <span>{{ $post->category }}</span>
        </div>
        
        @if($post->tags->count() > 0)
        <div class="flex flex-wrap gap-1 mb-4">
            @foreach($post->tags as $tag)
            <a href="{{ route('post.index', ['tag' => $tag->slug]) }}" wire:navigate class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600">
                {{ $tag->name }}
            </a>
            @endforeach
        </div>
        @endif
        
        <div class="prose prose-zinc dark:prose-invert max-w-none">
            {!! nl2br(e($post->content)) !!}
        </div>
        
        @if($post->image)
            <div class="mt-4">
                <img src="data:image/jpeg;base64,{{ base64_encode($post->image) }}" alt="Post image" class="rounded-lg max-h-96">
            </div>
        @endif
        
        @if(auth()->id() === $post->user_id)
            <div class="flex justify-end mt-4 gap-2">
                <flux:button variant="outline" href="{{ route('post.edit', $post) }}" wire:navigate>
                    編輯
                </flux:button>
                <flux:button variant="danger" wire:click="$dispatch('openModal', 'delete-post-modal', {{ json_encode(['post' => $post->id]) }})">
                    刪除
                </flux:button>
            </div>
        @endif
    </flux:card>
    
    <div class="mb-6">
        <flux:heading size="lg" class="mb-4">留言 ({{ $post->comments->count() }})</flux:heading>
        
        <form wire:submit="addComment" class="mb-6">
            <flux:textarea
                wire:model="content"
                placeholder="寫下你的留言..."
                rows="3"
            ></flux:textarea>
            
            <div class="mt-2 flex justify-end">
                <flux:button type="submit" variant="primary">送出留言</flux:button>
            </div>
        </form>
        
        @forelse($post->comments->where('deleted', false) as $comment)
            <flux:card class="mb-3">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-medium">{{ $comment->user->name }}</span>
                        <span class="text-sm text-zinc-500">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    
                    @if(auth()->id() === $comment->user_id)
                        <flux:button variant="ghost" size="sm" wire:click="$dispatch('openModal', 'delete-comment-modal', {{ json_encode(['comment' => $comment->id]) }})">
                            <flux:icon.trash class="w-4 h-4" />
                        </flux:button>
                    @endif
                </div>
                
                <div class="prose prose-zinc dark:prose-invert max-w-none">
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </flux:card>
        @empty
            <div class="text-center py-4">
                <p class="text-zinc-600 dark:text-zinc-400">暫無留言</p>
            </div>
        @endforelse
    </div>
</div>