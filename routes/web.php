<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/posts', [PostController::class, 'index'])
    ->name('posts');

Route::get('/posts/{post:slug}', [PostController::class, 'show'])
    ->name('post');

Route::get('/authors/{user}', [PostController::class, 'index'])
    ->name('author');

Route::get('/promoted', [PostController::class, 'index'])
    ->name('promoted')->defaults('promoted', true);

Route::post('/posts/{post}/comment', [CommentController::class, 'store'])
    ->name('comment');

Route::delete('/comment/{comment}', [CommentController::class, 'delete'])
    ->name('comment.delete');