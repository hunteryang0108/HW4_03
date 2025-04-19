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
});

require __DIR__.'/auth.php';