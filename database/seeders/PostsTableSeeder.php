<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 獲取所有標籤和用戶
        $tags = Tag::all();
        $users = User::all();
        
        // 如果沒有用戶，就無法建立文章
        if ($users->isEmpty()) {
            $this->command->info('沒有找到用戶。請先執行用戶 seeder。');
            return;
        }
        
        // 產生示範文章
        $posts = [
            [
                'title' => '歡迎來到留言板！',
                'content' => "這是一個測試文章，歡迎所有人參與討論。\n\n留言板的規則很簡單：互相尊重，友善交流。",
                'tags' => ['公告', '分享'],
            ],
            [
                'title' => '如何使用標籤系統？',
                'content' => "我們最近更新了留言板，現在支援多標籤功能！\n\n發文時可以添加多個標籤，這樣其他用戶就能更容易找到感興趣的主題。",
                'tags' => ['教學', '公告'],
            ],
            [
                'title' => '有人可以推薦學習資源嗎？',
                'content' => "我是剛開始學習 Laravel 的新手，有沒有推薦的學習資源或教學網站呢？感謝！",
                'tags' => ['問題', '求助'],
            ],
            [
                'title' => '分享一個有趣的開發經驗',
                'content' => "最近在開發專案時遇到一個有趣的問題...\n\n經過研究後發現原來是...\n\n希望我的經驗能幫助到大家！",
                'tags' => ['分享', '討論'],
            ],
        ];
        
        foreach ($posts as $postData) {
            // 隨機選擇一個用戶作為文章作者
            $user = $users->random();
            
            $post = Post::create([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'user_id' => $user->id,
                'deleted' => false,
            ]);
            
            // 為文章添加標籤
            $postTags = collect($postData['tags'])->map(function ($tagName) use ($tags) {
                return $tags->where('name', $tagName)->first()->id ?? null;
            })->filter();
            
            $post->tags()->sync($postTags);
        }
        
        $this->command->info('成功創建示範文章！');
    }
}