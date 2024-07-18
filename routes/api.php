<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['token'])->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('user', [AuthenticatedSessionController::class, 'getUser']);
            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                        ->middleware('auth')
                        ->name('logout');
        });

        Route::resource('blog', BlogController::class);
        Route::post('blog/update/{id}', [BlogController::class, 'update']);
        Route::resource('post', PostController::class);
        Route::get('get-posts/{blog_id}', [PostController::class, 'index']);
        Route::post('post/update/{id}', [PostController::class, 'update']);
        Route::prefix('like')->controller(LikeController::class)->group(function () {
            Route::post('', 'store');
        });
        Route::prefix('comment')->controller(CommentController::class)->group(function () {
            Route::post('', 'store');
            Route::get('/{id}', 'show');
            Route::post('update/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });
    });
});