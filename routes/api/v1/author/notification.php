<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\Api\V2\AuthorController;
use App\Http\Controllers\Api\V2\SettingController;
use App\Http\Controllers\v1\Author\Category\CategoryController;
use App\Http\Controllers\v1\Author\Notification\NotificationController;


Route::middleware(['cors','lang'])->prefix('v1/author')->group(function () {
    // Public settings route (no authentication required)
    Route::get('settings', [SettingController::class, 'getSettings']);
    Route::get('/banners', [SettingController::class, 'index']);
    Route::get('/publish-policy', [SettingController::class, 'getPublishPolicy']);
    Route::get('countries', [CountryController::class, 'index']);
    Route::get('sections', [SectionController::class, 'index']);
    Route::get('settings/{key}', [SettingController::class, 'getSetting']);
    Route::get('/authors', [AuthorController ::class, 'getAuthors']);
    Route::get('/verified-authors', [AuthorController ::class, 'getVerifiedAuthors']);
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('notification/unread', [NotificationController::class, 'getUnread']);
        Route::get('notification', [NotificationController::class, 'getNotifications']);
        Route::delete('notification/{notification}', [NotificationController::class, 'delete']);
        
        // Protected settings routes (update requires authentication)
      });
});
