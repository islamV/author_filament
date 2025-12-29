<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookV2Controller;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\v1\Author\Book\BookController;
use App\Http\Controllers\v1\Author\NotificationController;


Route::middleware(['cors', 'lang'])->prefix('v1/author')->group(function () {
    Route::get('books', [BookController::class, 'index']);//
    Route::get('categories', [BookController::class, 'category']);
    Route::get('get-home-page', [BookController::class, 'getHomePage']);//
    Route::get('get-featured-books', [BookController::class, 'getFeaturedBooks']);//
    Route::get('get-popular-books', [BookController::class, 'getPopularBooks']);//

    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('books/{book}', [BookController::class, 'show']);//
        Route::get('get-author-books', [BookController::class, 'getAuthorBooks']);
        Route::get('get-author-book/{book}', [BookV2Controller::class, 'getAuthorBook']);
        Route::get('get-author-book/{book}/user/{user}', [BookController::class, 'getAuthorBookByAuthorId']);
        
        Route::post('books/{book}/rate', [BookController::class, 'rate']);
        Route::get('books/{book}/rate', [BookController::class, 'getRate']);
        Route::get('books/category/{category}', [BookController::class, 'getBooksByCategory']);

//        Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
        
        Route::get('/books/{book}/download', [BookController::class, 'download']);
        Route::get('/books/{book}/delete-download', [BookController::class, 'deleteDownload']);
        Route::get('/books/{book}/refuse-reason', [BookController::class, 'bookRefuseReason']);


        Route::post('/invitation-code', [BookController::class, 'createInvitationCode']);

    });


    Route::middleware(['auth:sanctum', 'author'])->group(function () {
        Route::post('books', [BookController::class, 'store']); // create book
        Route::post('books/{book}', [BookV2Controller::class, 'update']); // update book
        Route::delete('books/{book}', [BookController::class, 'destroy']); // delete book
        Route::patch('publish-multiple-books/{book}', [BookController::class, 'publishMore']); // publish books
        Route::post('/send-multiple-notifications', [NotificationController::class, 'sendMultipleNotifications']); // send notifications
    });



    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/comments', [CommentController::class, 'store']);
        Route::post('/comments/{id}', [CommentController::class, 'update']);
        Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
        Route::post('/comments/{comment}/reply', [CommentController::class, 'reply']);
    });

    Route::get('/books/{book}/comments', [CommentController::class, 'getBookComments']);
    
});
