<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get all posts and users
        $posts = Post::all();
        $users = User::all();
        
        // If there are no posts or users, we can't create favorites
        if ($posts->isEmpty() || $users->isEmpty()) {
            $this->command->info('No posts or users found. Please run posts and users seeders first.');
            return;
        }
        
        // For each user, add 1-3 random favorites
        foreach ($users as $user) {
            $randomPosts = $posts->random(rand(1, min(3, $posts->count())));
            
            foreach ($randomPosts as $post) {
                // Ensure we don't create duplicates
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ]);
            }
        }
        
        $this->command->info('Successfully created sample favorites!');
    }
}