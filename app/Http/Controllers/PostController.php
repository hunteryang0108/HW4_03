<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // 顯示所有文章
    public function index(Request $request)
    {
        $tag = $request->query('tag');
        
        $query = Post::where('deleted', false)
                     ->with(['user', 'tags'])
                     ->orderBy('created_at', 'desc');
                     
        // 如果有標籤參數，則過濾文章
        if ($tag) {
            $query->whereHas('tags', function ($q) use ($tag) {
                $q->where('slug', $tag);
            });
        }
        
        $posts = $query->paginate(10);
        $allTags = Tag::orderBy('posts_count', 'desc')->take(20)->get();
        
        return view('posts.index', [
            'posts' => $posts,
            'tags' => $allTags,
            'currentTag' => $tag
        ]);
    }
    
    // 顯示創建文章表單
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        return view('posts.create', compact('tags'));
    }
    
    // 儲存新文章
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 最大2MB
        ]);
        
        $post = new Post([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);
        
        // 處理圖片上傳
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('post-images', 'public');
            $post->image = $path;
        }
        
        $post->save();
        
        // 在 app/Http/Controllers/PostController.php 的 store 或 update 方法中
        // 處理標籤
        if (isset($validated['tags'])) {
            // 處理可能的 JSON 格式
            if (is_string($validated['tags'])) {
                if (str_starts_with($validated['tags'], '[')) {
                    // 可能是 JSON 數組
                    $tagNames = json_decode($validated['tags'], true) ?: explode(',', $validated['tags']);
                } else {
                    // 普通的逗號分隔文本
                    $tagNames = explode(',', $validated['tags']);
                }
            } else {
                $tagNames = $validated['tags'];
            }
            
            $post->syncTagNames($tagNames);
        }
        
        return redirect()->route('posts.show', $post)
            ->with('success', '文章發佈成功！');
    }
    
    // 顯示單篇文章
    public function show(Post $post)
    {
        if ($post->deleted) {
            abort(404);
        }
        
        $post->load(['user', 'tags', 'comments.user']);
        
        return view('posts.show', compact('post'));
    }
    
    // 顯示編輯文章表單
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        
        $tags = Tag::orderBy('name')->get();
        $postTags = $post->tags->pluck('name')->implode(',');
        
        return view('posts.edit', compact('post', 'tags', 'postTags'));
    }
    
    // 更新文章
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
        
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        
        // 處理圖片更新
        if ($request->hasFile('image')) {
            // 刪除舊圖片
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $path = $request->file('image')->store('post-images', 'public');
            $post->image = $path;
        }
        
        $post->save();
        
        // 處理標籤
        if (isset($validated['tags'])) {
            $tagNames = explode(',', $validated['tags']);
            $post->syncTagNames($tagNames);
        } else {
            $post->tags()->detach();
        }
        
        return redirect()->route('posts.show', $post)
            ->with('success', '文章更新成功！');
    }
    
    public function destroy(Post $post){
        $this->authorize('delete', $post);
        
        $postId = $post->id;
        
        // 軟刪除
        $post->deleted = true;
        $post->save();
        
        return redirect()->route('posts.index')
            ->with('success', '文章已成功刪除');
    }
}