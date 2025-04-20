<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $existing = Like::where('user_id', $user->id)
                        ->where('post_id', $post->id)
                        ->first();
                        
        if ($existing) {
            $existing->delete();
            $action = 'unliked';
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $action = 'liked';
        }
        
        if (request()->wantsJson()) {
            return response()->json([
                'action' => $action,
                'count' => $post->likes()->count()
            ]);
        }
        
        return back();
    }
}