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
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label for="title" class="block font-medium text-sm mb-2">標題</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                        class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-shadow bg-white dark:bg-zinc-800"
                        required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                
                <!-- 標籤部分 -->
                <div class="mb-4">
                    <label for="tags" class="block font-medium text-sm mb-2">標籤 (可多選)</label>
                    <div class="tagify-wrapper relative">
                        <input id="tags" name="tags" value="{{ $postTags ?? '' }}" 
                            class="w-full px-4 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-shadow bg-white dark:bg-zinc-800">
                        <div class="absolute right-0 top-0 h-full flex items-center pr-3 text-zinc-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                    </div>
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
                    <button type="submit" 
                            class="px-4 py-2 bg-accent text-accent-foreground rounded-md transition-all active-press"
                            onclick="this.classList.add('opacity-75'); this.innerHTML='<span class=\'inline-block animate-spin mr-2\'>↻</span>處理中...';">
                        發佈文章
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    
    <script>
        // 使用 Tagify 實現標籤自動完成功能
        document.addEventListener('DOMContentLoaded', function() {
            const tagsInput = document.getElementById('tags');
            
            // 獲取所有標籤數據
            fetch('{{ route("api.tags") }}')
                .then(response => response.json())
                .then(tagsData => {
                    // 初始化 Tagify
                    const tagify = new Tagify(tagsInput, {
                        whitelist: tagsData,
                        enforceWhitelist: true,
                        dropdown: {
                            maxItems: 20,
                            enabled: 0, // 點擊時自動顯示下拉菜單
                            closeOnSelect: false
                        }
                    });
                    
                    // 點擊輸入框時顯示標籤選擇器
                    tagsInput.addEventListener('click', function() {
                        tagify.dropdown.show();
                    });
                })
                .catch(error => {
                    console.error('無法獲取標籤數據:', error);
                });
        });
    </script>
</x-layouts.app>