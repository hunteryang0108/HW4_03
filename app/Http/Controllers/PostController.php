<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::where('deleted', false)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category' => $request->input('category'),
            'user_id' => Auth::id(),
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $post->image = file_get_contents($image->getRealPath());
        }

        $post->save();

        return redirect()->route('posts.show', $post)->with('success', '文章已成功發布！');
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if ($post->deleted) {
            abort(404);
        }

        $comments = $post->comments()
                        ->where('deleted', false)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
        
        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        // 確保用戶只能編輯自己的文章
        if (Auth::id() !== $post->user_id) {
            abort(403, '無權編輯此文章');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // 確保用戶只能更新自己的文章
        if (Auth::id() !== $post->user_id) {
            abort(403, '無權更新此文章');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->category = $request->input('category');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $post->image = file_get_contents($image->getRealPath());
        }

        $post->save();

        return redirect()->route('posts.show', $post)->with('success', '文章已成功更新！');
    }

    /**
     * Remove the specified post from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // 確保用戶只能刪除自己的文章
        if (Auth::id() !== $post->user_id) {
            abort(403, '無權刪除此文章');
        }

        // 軟刪除文章
        $post->deleted = true;
        $post->save();

        return redirect()->route('posts.index')->with('success', '文章已成功刪除！');
    }
    
    /**
     * Display the image for the post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function image(Post $post)
    {
        if ($post->image) {
            return response($post->image)->header('Content-Type', 'image/jpeg');
        }
        
        abort(404);
    }
}