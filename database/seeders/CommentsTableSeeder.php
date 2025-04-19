<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 獲取所有文章和用戶
        $posts = Post::all();
        $users = User::all();
        
        // 如果沒有文章或用戶，就無法建立評論
        if ($posts->isEmpty() || $users->isEmpty()) {
            $this->command->info('沒有找到文章或用戶。請先執行文章和用戶 seeder。');
            return;
        }
        
        // 範例評論內容
        $commentContents = [
            "很棒的分享，謝謝！",
            "我有同樣的問題，希望有人能解答。",
            "這個解決方案對我很有幫助，感謝分享！",
            "我想補充一點，可能會對大家有用...",
            "我不太同意你的觀點，我認為...",
            "這篇文章寫得真好，內容很充實！",
            "有人知道這個問題有沒有其他解決方法嗎？",
            "我最近也遇到類似的情況，我是這樣處理的...",
            "感謝分享經驗，學到很多！",
            "這個主題很有趣，希望可以有更多討論。"
        ];
        
        // 為每篇文章添加 2-5 條評論
        foreach ($posts as $post) {
            $commentCount = rand(2, 5);
            
            for ($i = 0; $i < $commentCount; $i++) {
                // 隨機選擇一個用戶和評論內容
                $user = $users->random();
                $content = $commentContents[array_rand($commentContents)];
                
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                    'content' => $content,
                    'deleted' => false,
                ]);
            }
        }
        
        $this->command->info('成功創建示範評論！');
    }
}