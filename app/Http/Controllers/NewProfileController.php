<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\NewProfile;

class NewProfileController extends Controller
{
    /**
     * 顯示使用者的個人檔案。
     */
    public function show(User $user)
    {
        // 獲取未刪除的貼文
        $posts = $user->posts()->where('deleted', false)->orderBy('created_at', 'desc')->get();
        
        // 獲取未刪除的留言
        $comments = $user->comments()->where('deleted', false)->orderBy('created_at', 'desc')->get();
        
        // 獲取收藏的貼文
        $favoritePosts = $user->favoritePosts()->where('deleted', false)->orderBy('favorites.created_at', 'desc')->get();
        
        return view('profile.show', compact('user', 'posts', 'comments', 'favoritePosts'));
    }

    /**
     * 顯示編輯個人檔案的表單。
     */
    public function edit(User $user)
    {
        return view('profile.edit', compact('user'));
    }

    /**
     * 更新個人檔案資料。
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'birth'  => 'nullable|date',
            'bio'    => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // 確保 profile 存在（若無則建立）
        $profile = $user->profile ?? $user->profile()->create();

        $profile->birth = $request->birth;
        $profile->bio = $request->bio;

        if ($request->hasFile('avatar')) {
            if ($profile->avatar && Storage::disk('public')->exists('avatars/' . $profile->avatar)) {
                Storage::disk('public')->delete('avatars/' . $profile->avatar);
            }

            $filename = uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('avatars', $filename, 'public');
            $profile->avatar = $filename;
        }

        $profile->save();

        return redirect()->route('profile.show', $user)->with('success', '個人檔案已更新');
    }
}
