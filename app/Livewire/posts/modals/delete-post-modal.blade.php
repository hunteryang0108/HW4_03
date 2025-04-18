
<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {
    public Post $post;
    
    public function mount(int $post): void
    {
        $this->post = Post::findOrFail($post);
    }
    
    public function deletePost(): void
    {
        // 確保只有貼文擁有者可以刪除
        if (auth()->id() !== $this->post->user_id) {
            return;
        }
        
        // 軟刪除 - 設置 deleted 為 true
        $this->post->update(['deleted' => true]);
        
        $this->dispatch('closeModal');
        $this->redirect(route('post.index'), navigate: true);
    }
}; ?>

<div>
    <flux:heading size="lg" class="mb-4">確認刪除</flux:heading>
    
    <p class="mb-4">確定要刪除此貼文嗎？此操作無法復原。</p>
    
    <div class="flex justify-end gap-2">
        <flux:button variant="outline" wire:click="$dispatch('closeModal')">
            取消
        </flux:button>
        
        <flux:button variant="danger" wire:click="deletePost">
            確認刪除
        </flux:button>
    </div>
</div>