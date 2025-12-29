<?php


use App\Http\Controllers\v1\Author\book_parts\book_partsController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1/author')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('book_parts/{book_id}', [book_partsController::class, 'index']);
        Route::get('book_parts/{book_part}/part', [book_partsController::class, 'show']);
        Route::post('book_parts', [book_partsController::class, 'store']);
        Route::patch('book_parts/{book_parts}', [book_partsController::class, 'update']);
        Route::delete('book_parts/{book_parts}', [book_partsController::class, 'destroy']);
    });
});
