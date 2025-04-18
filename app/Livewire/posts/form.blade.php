
<?php

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new #[Layout('components.layouts.app')] class extends Component {
    use WithFileUploads;
    
    public ?Post $post = null;
    public $isEdit = false;
    
    #[Rule('required|min:3|max:255')]
    public $title = '';
    
    #[Rule('required|min:10')]
    public $content = '';
    
    #[Rule('required')]
    public $category = '';
    
    #[Rule('nullable|image|max:2048')]
    public $image = null;
    
    public function mount(Post $post = null): void
    {
        if ($post->exists) {
            $this->post = $post;
            $this->isEdit = true;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->category = $post->category;
        }
    }
    
    public function save(): void
    {
        $this->validate();
        
        $data = [
            'title' => $this->title,
            'content' => $this->content,
            'category' => $this->category,
        ];
        
        if ($this->image) {
            $data['image'] = $this->image->get();
        }
        
        if ($this->isEdit) {
            $this->post->update($data);
            $postId = $this->post->id;
        } else {
            $post = auth()->user()->posts()->create($data);
            $postId = $post->id;
        }
        
        $this->redirect(route('post.show', $postId), navigate: true);
    }
    
    public function with(): array
    {
        return [
            'categories' => [
                '一般討論', '問題求助', '技術分享', '課程討論', '閒聊'
            ],
        ];
    }
}; ?>

<div>
    <div class="mb-4">
        <flux:link href="{{ $isEdit ? route('post.show', $post) : route('post.index') }}" wire:navigate>
            <flux:icon.arrow-left class="w-4 h-4 inline mr-1" /> 返回
        </flux:link>
    </div>

    <flux:card>
        <flux:heading size="xl" class="mb-6">{{ $isEdit ? '編輯貼文' : '新增貼文' }}</flux:heading>
        
        <form wire:submit="save" class="space-y-4">
            <flux:input 
                wire:model="title" 
                label="標題" 
                placeholder="輸入貼文標題" 
                required 
            />
            
            <flux:select 
                wire:model="category" 
                label="分類" 
                placeholder="選擇分類" 
                required
            >
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </flux:select>
            
            <flux:textarea 
                wire:model="content" 
                label="內容" 
                placeholder="輸入貼文內容" 
                rows="10" 
                required 
            />
            
            <div>
                <flux:file 
                    wire:model="image" 
                    label="圖片 (選填)" 
                    accept="image/*" 
                />
                
                @if($isEdit && $post->image)
                    <div class="mt-2">
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-1">目前圖片：</p>
                        <img src="data:image/jpeg;base64,{{ base64_encode($post->image) }}" alt="Current image" class="rounded-lg h-32">
                    </div>
                @endif
            </div>
            
            <div class="flex justify-end gap-2">
                <flux:button type="button" variant="outline" href="{{ $isEdit ? route('post.show', $post) : route('post.index') }}" wire:navigate>
                    取消
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $isEdit ? '更新' : '發佈' }}
                </flux:button>
            </div>
        </form>
    </flux:card>
</div>