import.meta.glob([ '../images/**' ]);
// 引入 Tagify
import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

// 當頁面載入完成時，初始化 Tagify
document.addEventListener('DOMContentLoaded', function() {
    initializeTagify();
    
    // 監聽 Livewire 事件，以處理動態加載的頁面
    document.addEventListener('livewire:navigated', function() {
        initializeTagify();
    });
});

function initializeTagify() {
    const tagsInput = document.getElementById('tags');
    
    // 如果有標籤輸入框，初始化 Tagify
    if (tagsInput) {
        // 先檢查是否已經初始化過
        if (tagsInput.tagify) {
            return;
        }
        
        // 創建標籤選擇器
        const tagify = new Tagify(tagsInput, {
            whitelist: [],            // 將從服務器加載標籤列表
            enforceWhitelist: true,   // 強制只能選擇白名單中的標籤
            maxTags: 10,              // 最多可以選10個標籤
            editTags: false,          // 禁用標籤編輯
            dropdown: {
                maxItems: 20,         // 下拉菜單最多顯示20個項目
                enabled: 1,           // 始終啟用下拉菜單
                closeOnSelect: false, // 選擇後不關閉下拉菜單
                highlightFirst: true, // 自動高亮第一個選項
                position: "all"       // 下拉菜單位置
            }
        });
        
        // 保存tagify實例到元素上
        tagsInput.tagify = tagify;
        
        // 載入標籤列表
        fetch('/tags')
            .then(response => response.json())
            .then(data => {
                console.log("載入標籤:", data.length, "個");
                
                // 設置標籤列表
                tagify.settings.whitelist = data.map(tag => tag.name);
                
                // 如果輸入框已有初始值，處理它
                if (tagsInput.value) {
                    const initialTags = tagsInput.value.split(',')
                        .map(tag => tag.trim())
                        .filter(tag => tag);
                    
                    // 設置初始標籤
                    if (initialTags.length > 0) {
                        tagify.addTags(initialTags);
                    }
                }
                
                // 顯示下拉菜單
                setTimeout(() => {
                    tagify.DOM.input.focus();
                    tagify.dropdown.show.call(tagify);
                }, 100);
            })
            .catch(error => {
                console.error('無法載入標籤:', error);
            });
        
        // 監聽標籤添加事件
        tagify.on('add', function(e) {
            console.log("添加標籤:", e.detail.data.value);
        });
        
        // 監聽標籤刪除事件
        tagify.on('remove', function(e) {
            console.log("刪除標籤:", e.detail.data.value);
        });
        
        // 監聽輸入事件
        tagify.on('input', function(e) {
            const value = e.detail.value;
            
            if (value.length < 1) {
                // 顯示所有標籤
                tagify.dropdown.show.call(tagify);
                return;
            }
            
            tagify.loading(true);
            
            // 根據輸入搜索標籤
            fetch(`/tags/search?q=${value}`)
                .then(response => response.json())
                .then(data => {
                    tagify.settings.whitelist = data.map(tag => tag.name);
                    tagify.loading(false);
                    tagify.dropdown.show.call(tagify, value);
                })
                .catch(error => {
                    console.error('搜索標籤時出錯:', error);
                    tagify.loading(false);
                });
        });
        
        // 處理表單提交
        const form = tagsInput.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                if (tagify.value.length) {
                    // 轉換成逗號分隔的字符串
                    const tagValues = tagify.value.map(item => item.value).join(',');
                    tagsInput.value = tagValues;
                }
            });
        }
    }
}

// 搜尋功能實現
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchDropdown = document.getElementById('search-dropdown');
    const suggestionsContent = document.getElementById('suggestions-content');
    
    if (searchInput) {
        // 監聽輸入事件
        searchInput.addEventListener('input', debounce(function() {
            const query = searchInput.value.trim();
            
            // 獲取搜尋選項
            const inTitle = document.getElementById('search-title')?.checked || false;
            const inContent = document.getElementById('search-content')?.checked || false;
            const inUser = document.getElementById('search-user')?.checked || false;
            
            // 構建參數
            let params = new URLSearchParams();
            params.append('query', query);
            if (inTitle) params.append('in_title', '1');
            if (inContent) params.append('in_content', '1');
            if (inUser) params.append('in_user', '1');
            
            // 獲取建議
            fetch(`/search/suggestions?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsContent.innerHTML = '';
                    
                    if (data.length === 0) {
                        suggestionsContent.innerHTML = '<div class="px-4 py-2 text-zinc-500 dark:text-zinc-400 text-sm">沒有相關建議</div>';
                    } else {
                        data.forEach(post => {
                            const item = document.createElement('a');
                            item.href = `/posts/${post.id}`;
                            item.className = 'block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors';
                            
                            const content = document.createElement('div');
                            content.className = 'flex items-center';
                            
                            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                            icon.setAttribute('class', 'h-4 w-4 mr-2 text-zinc-400');
                            icon.setAttribute('fill', 'none');
                            icon.setAttribute('viewBox', '0 0 24 24');
                            icon.setAttribute('stroke', 'currentColor');
                            
                            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                            path.setAttribute('stroke-linecap', 'round');
                            path.setAttribute('stroke-linejoin', 'round');
                            path.setAttribute('stroke-width', '2');
                            path.setAttribute('d', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z');
                            
                            icon.appendChild(path);
                            content.appendChild(icon);
                            content.appendChild(document.createTextNode(post.title));
                            
                            item.appendChild(content);
                            suggestionsContent.appendChild(item);
                        });
                    }
                    
                    searchDropdown.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('搜尋建議獲取失敗:', error);
                });
        }, 300));
        
        // 頁面載入時，如果輸入框為空，自動載入熱門推薦
        if (searchInput.value.trim() === '') {
            fetch('/search/suggestions')
                .then(response => response.json())
                .then(data => {
                    suggestionsContent.innerHTML = '';
                    
                    if (data.length === 0) {
                        suggestionsContent.innerHTML = '<div class="px-4 py-2 text-zinc-500 dark:text-zinc-400 text-sm">沒有熱門推薦</div>';
                    } else {
                        data.forEach(post => {
                            const item = document.createElement('a');
                            item.href = `/posts/${post.id}`;
                            item.className = 'block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors';
                            
                            const content = document.createElement('div');
                            content.className = 'flex items-center';
                            
                            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                            icon.setAttribute('class', 'h-4 w-4 mr-2 text-zinc-400');
                            icon.setAttribute('fill', 'none');
                            icon.setAttribute('viewBox', '0 0 24 24');
                            icon.setAttribute('stroke', 'currentColor');
                            
                            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                            path.setAttribute('stroke-linecap', 'round');
                            path.setAttribute('stroke-linejoin', 'round');
                            path.setAttribute('stroke-width', '2');
                            path.setAttribute('d', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z');
                            
                            icon.appendChild(path);
                            content.appendChild(icon);
                            content.appendChild(document.createTextNode(post.title));
                            
                            item.appendChild(content);
                            suggestionsContent.appendChild(item);
                        });
                    }
                })
                .catch(error => {
                    console.error('熱門推薦獲取失敗:', error);
                });
        }
        
        // 點擊搜尋框時顯示下拉框
        searchInput.addEventListener('focus', function() {
            searchDropdown.classList.remove('hidden');
        });
        
        // 點擊外部時隱藏下拉框
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
                searchDropdown.classList.add('hidden');
            }
        });
    }
    
    // 防抖函數
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
});