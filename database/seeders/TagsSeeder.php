<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => '問題', 'slug' => 'question', 'color' => 'red-500'],
            ['name' => '討論', 'slug' => 'discussion', 'color' => 'blue-500'],
            ['name' => '分享', 'slug' => 'share', 'color' => 'green-500'],
            ['name' => '教學', 'slug' => 'tutorial', 'color' => 'yellow-500'],
            ['name' => '公告', 'slug' => 'announcement', 'color' => 'purple-500'],
            ['name' => '閒聊', 'slug' => 'chat', 'color' => 'gray-500'],
            ['name' => '求助', 'slug' => 'help', 'color' => 'orange-500'],
            ['name' => '建議', 'slug' => 'suggestion', 'color' => 'pink-500'],
            ['name' => '資源', 'slug' => 'resource', 'color' => 'indigo-500'],
            ['name' => '其他', 'slug' => 'other', 'color' => 'teal-500'],
        ];
        
        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag['name'],
                'slug' => $tag['slug'],  // 使用預定義的 slug
                'color' => $tag['color'],
            ]);
        }
    }
}

