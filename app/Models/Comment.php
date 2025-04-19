<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'deleted'
    ];
    
    // 與文章的關聯
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    
    // 與使用者的關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}