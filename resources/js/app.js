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
        const tagify = new Tagify(tagsInput, {
            whitelist: [],
            maxTags: 10,
            dropdown: {
                maxItems: 10,
                classname: "tags-dropdown",
                enabled: 0,
                closeOnSelect: false
            }
        });
        
        // 載入現有的標籤
        fetch('/tags')
            .then(response => response.json())
            .then(data => {
                tagify.settings.whitelist = data.map(tag => tag.name);
            });
        
        // 輸入時搜尋標籤
        tagify.on('input', e => {
            const value = e.detail.value;
            tagify.settings.whitelist.length = 0; // 重置
            
            if (value.length < 1) return;
            
            tagify.loading(true);
            
            fetch(`/tags/search?q=${value}`)
                .then(response => response.json())
                .then(data => {
                    tagify.settings.whitelist = data.map(tag => tag.name);
                    tagify.loading(false);
                    tagify.dropdown.show.call(tagify, value);
                });
        });
    }
}