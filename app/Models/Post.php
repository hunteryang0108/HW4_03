<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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


    /**
     * Get the favorites for the post.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
    /**
     * Get the users who favorited this post.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'post_id', 'user_id')->withTimestamps();
    }
    
    /**
     * Check if the post is favorited by a user.
     */
    public function isFavoritedBy(User $user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }
    
   
    // 在 app/Models/Post.php 中的 syncTagNames 方法

    public function syncTagNames(array $tagNames)
    {
        // 過濾空值
        $tagNames = array_filter($tagNames, fn($name) => !empty($name));
        
        // 只取現有標籤
        $existingTags = Tag::whereIn('name', $tagNames)->get();
        
        // 同步標籤
        $this->tags()->sync($existingTags->pluck('id'));
        
        // 更新標籤計數
        Tag::withCount('posts')->get()->each(function ($tag) {
            $tag->posts_count = $tag->posts_count;
            $tag->save();
        });
    }
}