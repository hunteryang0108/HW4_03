<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for a post.
     */
    public function toggle(Post $post)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $existing = Favorite::where('user_id', $user->id)
                          ->where('post_id', $post->id)
                          ->first();
                        
        if ($existing) {
            $existing->delete();
            $action = 'unfavorited';
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $action = 'favorited';
        }
        
        if (request()->wantsJson()) {
            return response()->json([
                'action' => $action,
                'count' => $post->favorites()->count()
            ]);
        }
        
        return back()->with('success', "Post has been {$action}");
    }
    
    /**
     * Display a list of user's favorite posts.
     */
    public function index()
    {
        $user = Auth::user();
        $favoritePosts = $user->favoritePosts()->where('deleted', false)->paginate(10);
        
        return view('favorites.index', compact('favoritePosts'));
    }
}