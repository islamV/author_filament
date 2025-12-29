<?php


use App\Http\Controllers\v1\Author\Follow\FollowController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->group(function () {
    Route::middleware('auth:sanctum')->prefix("v1/author")->group(function () {
        Route::post('toggle-follow/{user}', [FollowController::class, 'toggleFollow'])->name('toggle-follow');
        Route::get('users/followers', [FollowController::class, 'followers'])->name('users.followers');
        Route::get('users/{user}/following', [FollowController::class, 'following'])->name('users.following');
    });
});
