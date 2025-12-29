<?php


use App\Http\Controllers\v1\Author\Auth\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors', 'lang', 'auth:sanctum'])->prefix('v1')->group(function () {
    Route::post('add-book-to-favourite/{book}', [UserController::class, 'favorite']);
    Route::get('get-books-from-favourites', [UserController::class, 'getFavouriteBooks']);

    Route::get('get-user-profile', [UserController::class, 'getUserProfile'])
        ->middleware('role:User');

    Route::get('get-author-profile', [UserController::class, 'getAuthorProfile'])
        ->middleware('role:Author,Verified Author');

    Route::post('get-revenue-calculation', [UserController::class, 'getViews'])
        ->middleware('role:Author,Verified Author');

    Route::post('update-user-profile', [UserController::class, 'updateUserProfile'])
        ->middleware('role:User,Author,Verified Author');

    Route::get('get-author-profile-reader/{user}', [UserController::class, 'AuthorPage'])->middleware('role:User,Author,Verified Author');

    Route::patch('/users/change-to-reader', [UserController::class, 'convertToReader']);
});

