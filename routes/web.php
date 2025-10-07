<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\TweetLikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/tweets/{tweet}/pin', [TweetController::class, 'pin'])->name('tweets.pin');
    Route::delete('/tweets/{tweet}/unpin', [TweetController::class, 'unpin'])->name('tweets.unpin');
    Route::post('tweets/{tweet}/bookmark', [BookmarkController::class, 'store'])->name('tweets.bookmark');
    Route::delete('tweets/{tweet}/unbookmark', [BookmarkController::class, 'destroy'])->name('tweets.unbookmark');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index')->middleware('auth');
    Route::get('/tweets', [TweetController::class, 'index'])->name('tweets.index');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::get('/tweets/search', [TweetController::class, 'search'])->name('tweets.search');
    Route::resource('tweets', TweetController::class);
    Route::post('/tweets/{tweet}/like', [TweetLikeController::class, 'store'])->name('tweets.like');
    Route::delete('/tweets/{tweet}/like', [TweetLikeController::class, 'destroy'])->name('tweets.dislike');
    Route::resource('tweets.comments', CommentController::class);
    Route::post('/follow/{user}', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user}', [FollowController::class, 'destroy'])->name('follow.destroy');
});

require __DIR__.'/auth.php';
