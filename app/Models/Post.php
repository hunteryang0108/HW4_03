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
    
    // 同步標籤名稱（輸入標籤名稱陣列）
    public function syncTagNames(array $tagNames)
    {
        $tags = collect($tagNames)->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => trim($tagName)]);
        });
        
        $this->tags()->sync($tags->pluck('id'));
        
        // 更新標籤的文章計數
        Tag::withCount('posts')->get()->each(function ($tag) {
            $tag->posts_count = $tag->posts_count;
            $tag->save();
        });
    }
}