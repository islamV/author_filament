<?php


use App\Http\Controllers\v1\Author\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('register-step2', [AuthController::class, 'registerstep2']);
    Route::post('send-otp', [AuthController::class, 'sendOneTimePassword']);
    Route::post('validate-otp', [AuthController::class, 'validateOTP']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/check-token', [AuthController::class, 'checkFCM']);
    Route::post('/social-login', [AuthController::class, 'loginWithGoogle']);
    Route::post('register-author', [AuthController::class, 'registerAuthor']);
    Route::patch('change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});



