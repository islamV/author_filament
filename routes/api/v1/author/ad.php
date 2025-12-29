<?php


use App\Http\Controllers\v1\Author\Ad\AdController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('ads', [AdController::class, 'index']);
        Route::get('ads/{ad}', [AdController::class, 'show']);
    });
});
