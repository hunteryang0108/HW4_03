<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment([
            'content' => $request->input('content'),
            'user_id' => Auth::id(),
        ]);

        $post->comments()->save($comment);

        return redirect()->back()->with('success', '留言已成功發布！');
    }

    /**
     * Show the form for editing the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        // 確保用戶只能編輯自己的留言
        if (Auth::id() !== $comment->user_id) {
            abort(403, '無權編輯此留言');
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        // 確保用戶只能更新自己的留言
        if (Auth::id() !== $comment->user_id) {
            abort(403, '無權更新此留言');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('posts.show', $comment->post_id)->with('success', '留言已成功更新！');
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        // 確保用戶只能刪除自己的留言
        if (Auth::id() !== $comment->user_id) {
            abort(403, '無權刪除此留言');
        }

        $post_id = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $post_id)->with('success', '留言已成功刪除！');
    }
}