<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NewProfile extends Model
{
    protected $table = 'profiles';
    protected $primaryKey = 'user_id'; // 主鍵為 user_id
    public $incrementing = false;      // 非自動遞增主鍵

    protected $fillable = [
        'user_id',
        'birth',
        'bio',
        'avatar',
    ];

    protected $casts = [
        'birth' => 'date',
    ];

    /**
     * 與 User 的關聯（一對一，反向）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 回傳頭像的完整 URL
     */
    public function getAvatarUrl(): ?string
    {
        if ($this->avatar) {
            $base64Image = base64_encode($this->avatar);
            return "data:image/png;base64,{$base64Image}";
        }
        return null;
    }
    
}
