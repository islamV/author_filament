<?php


use App\Http\Controllers\v1\Author\Payment\ManualPaymentController;
use App\Http\Controllers\v1\Author\Payment\PaymentController;
use App\Http\Controllers\v1\Author\Subscription\SubscriptionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['cors','lang'])->prefix('v1')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('get-payment-gateway', [PaymentController::class, 'index']);
        Route::post('store-payment', [PaymentController::class, 'store']);
        Route::get('get-requested-payment', [PaymentController::class, 'getRequestedPayment']);
        Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
        Route::get('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription']);
        Route::post('/resume-subscription', [SubscriptionController::class, 'resumeSubscription']);
        Route::get('/subscription-status', [SubscriptionController::class, 'checkSubscription']);
        Route::get('/get-plans', [SubscriptionController::class, 'plans']);
       // Route::get('/manual-payments', [ManualPaymentController::class, 'index']);
        Route::post('/manual-payments', [ManualPaymentController::class, 'store']);
    });
});
