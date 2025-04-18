
<?php

use App\Models\Comment;
use Livewire\Volt\Component;

new class extends Component {
    public Comment $comment;
    
    public function mount(int $comment): void
    {
        $this->comment = Comment::findOrFail($comment);
    }
    
    public function deleteComment(): void
    {
        // 確保只有評論擁有者可以刪除
        if (auth()->id() !== $this->comment->user_id) {
            return;
        }
        
        // 軟刪除 - 設置 deleted 為 true
        $this->comment->update(['deleted' => true]);
        
        $this->dispatch('closeModal');
    }
}; ?>

<div>
    <flux:heading size="lg" class="mb-4">確認刪除</flux:heading>
    
    <p class="mb-4">確定要刪除此評論嗎？此操作無法復原。</p>
    
    <div class="flex justify-end gap-2">
        <flux:button variant="outline" wire:click="$dispatch('closeModal')">
            取消
        </flux:button>
        
        <flux:button variant="danger" wire:click="deleteComment">
            確認刪除
        </flux:button>
    </div>
</div>