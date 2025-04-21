<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // 儲存評論
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);
        
        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'post_id' => $post->id
        ]);
        
        $comment->save();
        
        return redirect()->route('posts.show', $post)
            ->with('success', '評論發佈成功！');
    }
    
    // 更新評論
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);
        
        $validated = $request->validate([
            'content' => 'required|string'
        ]);
        
        $comment->content = $validated['content'];
        $comment->save();
        
        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', '評論更新成功！');
    }
    
    // 刪除評論
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $postId = $comment->post_id;
        
        // 軟刪除
        $comment->deleted = true;
        $comment->save();
        
        // 清除缓存，确保评论计数立即更新
        $post = Post::find($postId);
        if ($post) {
            $post->load('comments'); // 重新加载评论关系
        }
        
        return redirect()->route('posts.show', $postId)
            ->with('success', '評論已成功刪除');
    }
}