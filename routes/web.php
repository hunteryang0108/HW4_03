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




// // 顯示個人資料
// Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// // 編輯個人資料（僅限本人）
// Route::get('/profile/{user}/edit', [NewProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');

// // 更新個人資料（僅限本人）
// Route::put('/profile/{user}', [NewProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// // 顯示頭像（如果你是從資料庫或 public 資料夾顯示）
// Route::get('/avatar/{user}', function ($userId) {
//     $user = \App\Models\User::findOrFail($userId);
//     $avatar = $user->profile?->avatar;

//     if (!$avatar) {
//         abort(404);
//     }

//     $path = storage_path("app/public/avatars/{$avatar}");

//     if (!file_exists($path)) {
//         abort(404);
//     }

//     return response()->file($path);
// })->name('avatar.show');

// use App\Http\Controllers\NewProfileController;

// Route::get('/new-profile', [NewProfileController::class, 'index']);
// Route::get('/profile/{user}', [NewProfileController::class, 'show'])->name('profile.show');
// Route::get('/profile/{user}/edit', [NewProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
// Route::put('/profile/{user}', [NewProfileController::class, 'update'])->middleware('auth')->name('profile.update');

// // 顯示頭像（如果你是從資料庫或 public 資料夾顯示）
// Route::get('/avatar/{user}', function ($userId) {
//     $user = \App\Models\User::findOrFail($userId);
//     $avatar = $user->profile?->avatar;

//     if (!$avatar) {
//         abort(404);
//     }

//     $path = storage_path("app/public/avatars/{$avatar}");

//     if (!file_exists($path)) {
//         abort(404);
//     }

//     return response()->file($path);
// })->name('avatar.show');

use App\Http\Controllers\NewProfileController;

// 新版個人檔案首頁（可自訂）
Route::get('/new-profile', [NewProfileController::class, 'index']);

// 顯示使用者個人檔案
Route::get('/profile/{user}', [NewProfileController::class, 'show'])->name('profile.show');

// 編輯使用者個人檔案（需登入）
Route::get('/profile/{user}/edit', [NewProfileController::class, 'edit'])
    ->middleware('auth')
    ->name('profile.edit');

// 更新使用者個人檔案（需登入）
Route::put('/profile/{user}', [NewProfileController::class, 'update'])
    ->middleware('auth')
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

require __DIR__.'/auth.php';
