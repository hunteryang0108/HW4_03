<?php

use App\Models\Post;
use App\Models\Tag;
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
    
    #[Rule('nullable|string')]
    public $tags = '';
    
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
            // 加載標籤
            $this->tags = $post->tags->pluck('name')->implode(',');
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
            
            // 更新標籤
            if ($this->tags) {
                $tagNames = explode(',', $this->tags);
                $this->post->syncTagNames($tagNames);
            } else {
                $this->post->tags()->detach();
            }
        } else {
            $post = auth()->user()->posts()->create($data);
            $postId = $post->id;
            
            // 處理標籤
            if ($this->tags) {
                $tagNames = explode(',', $this->tags);
                $post->syncTagNames($tagNames);
            }
        }
        
        $this->redirect(route('post.show', $postId), navigate: true);
    }
    
    public function with(): array
    {
        return [
            'categories' => [
                '一般討論', '問題求助', '技術分享', '課程討論', '閒聊'
            ],
            'allTags' => Tag::orderBy('name')->get()
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
            
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">標籤</label>
                <input
                    wire:model="tags"
                    type="text"
                    id="tags"
                    class="w-full px-3 py-2 border border-zinc-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-zinc-500 focus:border-zinc-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white"
                    placeholder="使用逗號分隔多個標籤"
                />
                <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">例如：Laravel,PHP,教學</p>
            </div>
            
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