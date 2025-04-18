<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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

    // 文章相關路由 - 使用 middleware 確保只有登入用戶能訪問
    Route::resource('posts', PostController::class);
    Route::get('/posts/{post}/image', [PostController::class, 'image'])->name('posts.image');

    // 留言相關路由 - 同樣使用 middleware 確保只有登入用戶能訪問
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
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
