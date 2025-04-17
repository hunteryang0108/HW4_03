<x-layouts.app :title="__('編輯文章')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-6">
            <flux:button href="{{ route('posts.show', $post) }}" variant="outline">
                <flux:icon.arrow-left class="mr-2" /> 返回文章
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6">
            <flux:heading size="xl" class="mb-6">編輯文章</flux:heading>

            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <flux:input 
                        name="title" 
                        label="標題" 
                        required 
                        value="{{ old('title', $post->title) }}"
                    />
                </div>
                
                <div class="mb-4">
                    <flux:input 
                        name="category" 
                        label="分類" 
                        required 
                        value="{{ old('category', $post->category) }}"
                    />
                </div>
                
                <div class="mb-4">
                    <flux:textarea 
                        name="content" 
                        label="內容" 
                        rows="10" 
                        required
                    >{{ old('content', $post->content) }}</flux:textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium">
                        圖片（選填）
                    </label>
                    @if($post->image)
                        <div class="mb-2">
                            <img src="{{ route('posts.image', $post) }}" 
                                alt="{{ $post->title }}" 
                                class="w-32 h-32 object-cover rounded">
                        </div>
                        <p class="text-sm mb-2">上傳新圖片將會替換原有圖片</p>
                    @endif
                    <input 
                        type="file" 
                        name="image" 
                        accept="image/*"
                        class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-lg file:border-0
                               file:text-sm file:font-semibold
                               file:bg-gray-50 file:text-gray-700
                               hover:file:bg-gray-100
                               dark:file:bg-zinc-700 dark:file:text-zinc-100
                               dark:hover:file:bg-zinc-600"
                    />
                </div>
                
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        更新文章
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>