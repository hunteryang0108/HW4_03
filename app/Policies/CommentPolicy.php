<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // 任何已登入的用戶都可以查看所有評論
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true; // 任何已登入的用戶都可以查看單一評論
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // 任何已登入的用戶都可以建立評論
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id; // 只有評論作者可以編輯評論
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // 評論作者或文章作者都可以刪除評論
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        // 評論作者或文章作者都可以恢復評論
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        // 評論作者或文章作者都可以永久刪除評論
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }
}