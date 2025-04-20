<?php

namespace Database\Seeders;

use App\Models\User;
//php artisan db:seed --class=DatabaseSeederuse Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

         // 先執行標籤 seeder
         $this->call(TagsSeeder::class);
        
         // 再執行文章 seeder
         $this->call(PostsTableSeeder::class); 
         
         // 最後執行留言 seeder
         $this->call(CommentsTableSeeder::class);

         // 最後執行收藏 seeder
         $this->call(FavoritesTableSeeder::class);
    }
}