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
