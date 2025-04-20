<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // 文章相關路由
    Route::resource('posts', PostController::class);
    
    // Livewire 版的留言板路由
    Volt::route('post', 'posts.index')->name('post.index');
    Volt::route('post/create', 'posts.form')->name('post.create');
    Volt::route('post/{post}/edit', 'posts.form')->name('post.edit');
    Volt::route('post/{post}', 'posts.show')->name('post.show');

    // 評論相關路由
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // 標籤相關路由
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/search', [TagController::class, 'search'])->name('tags.search');

    //like相關路由
    Route::post('/posts/{post}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('posts.like');

    // favorite相關路由
    Route::post('/posts/{post}/favorite', [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('posts.favorite');
    Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');

    // 標籤
    Route::get('/api/tags', function () {
        $tags = \App\Models\Tag::all()->map(function($tag) {
            return [
                'value' => $tag->id,
                'text' => $tag->name,
                'color' => $tag->color
            ];
        });
        
        return response()->json($tags);
    })->name('api.tags');
    });





use App\Http\Controllers\NewProfileController;

// 新版個人檔案首頁（可自訂）
// 只有登入用戶才能訪問
Route::middleware(['auth'])->group(function () {
    // 顯示使用者個人檔案
    Route::get('/profile/{user}', [NewProfileController::class, 'show'])->name('profile.show');

    // 編輯使用者個人檔案
    Route::get('/profile/{user}/edit', [NewProfileController::class, 'edit'])->name('profile.edit');

    // 更新使用者個人檔案
    Route::put('/profile/{user}', [NewProfileController::class, 'update'])
        ->name('profile.update');
    
    // 顯示使用者頭像
    Route::get('/avatar/{user}', function ($userId) {
        $user = \App\Models\User::findOrFail($userId);
        $avatar = $user->profile?->avatar;

        if (!$avatar) {
            abort(404);
        }

        $path = storage_path("app/public/avatars/{$avatar}");

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    })->name('avatar.show');
});


require __DIR__.'/auth.php';

    

