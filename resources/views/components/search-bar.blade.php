<div class="mb-6 mt-4">
    <form action="{{ route('search') }}" method="GET" class="relative">
        <div class="relative">
            <input type="text" name="query" id="search-input" 
                   class="w-full px-4 py-3 pl-10 pr-10 border border-zinc-300 dark:border-zinc-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-shadow bg-white dark:bg-zinc-800"
                   placeholder="搜尋文章..."
                   autocomplete="off">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400 hover:text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
        
        <!-- 搜尋建議與歷史紀錄下拉框 -->
        <div id="search-dropdown" class="hidden absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg shadow-lg overflow-hidden">
            <!-- 搜尋建議 -->
            <div id="search-suggestions" class="p-2">
                <div class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 px-3 py-2">
                    熱門推薦
                </div>
                <div id="suggestions-content" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    <!-- 會由 JavaScript 動態填充 -->
                </div>
            </div>
            
            <!-- 搜尋歷史 -->
            <div id="search-history" class="border-t border-zinc-100 dark:border-zinc-700 p-2">
                <div class="flex justify-between items-center text-sm font-semibold text-zinc-500 dark:text-zinc-400 px-3 py-2">
                    <span>最近搜尋</span>
                    <form action="{{ route('search.clear-history') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-red-500 hover:underline">清除紀錄</button>
                    </form>
                </div>
                <div id="history-content" class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @if (session('search_history'))
                        @foreach(session('search_history') as $item)
                            <a href="{{ route('search', ['query' => $item]) }}" class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $item }}
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="px-4 py-2 text-zinc-500 dark:text-zinc-400 text-sm">
                            沒有搜尋紀錄
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>