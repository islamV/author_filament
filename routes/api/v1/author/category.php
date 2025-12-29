<?php


use App\Http\Controllers\v1\Author\Category\CategoryController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1/author')->group(function () {
 
    
    Route::post('categories', [CategoryController::class, 'store']);
    Route::patch('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);
});
