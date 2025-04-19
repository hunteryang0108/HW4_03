<<<<<<< HEAD
<x-layouts.app :title="__('新增文章')">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center mb-6">
            <flux:button href="{{ route('posts.index') }}" variant="outline">
                <flux:icon.arrow-left class="mr-2" /> 返回列表
            </flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-800 shadow rounded-lg p-6">
            <flux:heading size="xl" class="mb-6">新增文章</flux:heading>

=======
<x-layouts.app title="發佈新文章">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-4">
            <a href="{{ route('posts.index') }}" class="text-accent hover:underline flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                返回列表
            </a>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">發佈新文章</h1>
            
>>>>>>> 0608ffd527b24f5bdeeb4aec3406f9fe1d9ab465
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
<<<<<<< HEAD
                    <flux:input 
                        name="title" 
                        label="標題" 
                        required 
                        value="{{ old('title') }}"
                    />
                </div>
                
                <div class="mb-4">
                    <flux:input 
                        name="category" 
                        label="分類" 
                        required 
                        value="{{ old('category') }}"
                    />
                </div>
                
                <div class="mb-4">
                    <flux:textarea 
                        name="content" 
                        label="內容" 
                        rows="10" 
                        required
                    >{{ old('content') }}</flux:textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium">
                        圖片（選填）
                    </label>
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
                        發布文章
                    </flux:button>
=======
                    <label for="title" class="block font-medium text-sm mb-2">標題</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="tags" class="block font-medium text-sm mb-2">標籤 (用逗號分隔)</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                           class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800">
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">例如：問題, 討論, 分享</p>
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="content" class="block font-medium text-sm mb-2">內容</label>
                    <textarea name="content" id="content" rows="10" 
                              class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="image" class="block font-medium text-sm mb-2">圖片（選填）</label>
                    <input type="file" name="image" id="image" accept="image/*"
                           class="w-full px-3 py-2 border border-zinc-300 dark:border-zinc-600 rounded-md focus:outline-none focus:ring focus:ring-accent focus:ring-opacity-50 bg-white dark:bg-zinc-800">
                    <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">支援的格式：JPG, PNG, GIF。最大檔案大小：2MB</p>
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-accent text-accent-foreground rounded-md">
                        發佈文章
                    </button>
>>>>>>> 0608ffd527b24f5bdeeb4aec3406f9fe1d9ab465
                </div>
            </form>
        </div>
    </div>
<<<<<<< HEAD
=======
    
    <script>
        // 使用 JavaScript 實現標籤自動完成功能
        document.addEventListener('DOMContentLoaded', function() {
            const tagsInput = document.getElementById('tags');
            
            // 這裡可以添加標籤自動完成的功能
            // 可以使用 Tagify 或其他 JS 庫來實現
        });
    </script>
>>>>>>> 0608ffd527b24f5bdeeb4aec3406f9fe1d9ab465
</x-layouts.app>