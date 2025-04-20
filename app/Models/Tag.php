<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color'];
    
    
   

    public function setNameAttribute($value)
    {
        // 處理可能的JSON格式
        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
            try {
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['value'])) {
                    $value = $decoded['value'];
                }
            } catch (\Exception $e) {
                // 保持原值
            }
        }
        
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    // 與文章的關聯
    public function posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }
}