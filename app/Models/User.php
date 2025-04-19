<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
    /**
     * Get the posts for the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    

    /**
     * Get the comments for the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 產生使用者頭像URL (基於名字的初始字母)
     */
    public function avatarUrl($size = 40): string
    {
        $name = urlencode($this->name);
        $bgColor = substr(md5($this->email), 0, 6);
        return "https://ui-avatars.com/api/?name={$name}&size={$size}&background={$bgColor}&color=ffffff";
    }
}
