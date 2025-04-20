<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image',
        'deleted'
    ];

    // 與標籤的關聯
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
    
    // 與作者的關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // 與評論的關聯
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //與like的關聯
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    
   // 在 app/Models/Post.php 文件中
    public function syncTagNames(array $tagNames)
    {
        $processedTagNames = [];
        
        foreach ($tagNames as $tagName) {
            // 處理可能的不同格式
            if (is_array($tagName) && isset($tagName['value'])) {
                // 如果是數組直接取 value
                $processedTagNames[] = $tagName['value'];
            } elseif (is_string($tagName)) {
                // 如果是字符串判斷是否為 JSON
                if (str_starts_with(trim($tagName), '{')) {
                    $decoded = json_decode($tagName, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['value'])) {
                        $processedTagNames[] = $decoded['value'];
                    } else {
                        $processedTagNames[] = trim($tagName);
                    }
                } else {
                    $processedTagNames[] = trim($tagName);
                }
            }
        }
        
        // 過濾空值
        $processedTagNames = array_filter($processedTagNames, fn($name) => !empty($name));
        
        // 創建標籤並同步
        $tags = collect($processedTagNames)->map(fn($name) => 
            Tag::firstOrCreate(['name' => $name])
        );
        
        $this->tags()->sync($tags->pluck('id'));
        
        // 更新標籤計數
        Tag::withCount('posts')->get()->each(function ($tag) {
            $tag->posts_count = $tag->posts_count;
            $tag->save();
        });
    }
}
