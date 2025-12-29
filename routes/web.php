<?php

use App\Http\Controllers\v1\Author\Dashboard\AdController;
use App\Http\Controllers\v1\Author\Dashboard\AdminController;
use App\Http\Controllers\v1\Author\Dashboard\AuthController;
use App\Http\Controllers\v1\Author\Dashboard\BookController;
use App\Http\Controllers\v1\Author\Dashboard\BookPartController;
use App\Http\Controllers\v1\Author\Dashboard\CategoryController;
use App\Http\Controllers\v1\Author\Dashboard\ChatController;
use App\Http\Controllers\v1\Author\Dashboard\PaymentController;
use App\Http\Controllers\v1\Author\Dashboard\SubscriptionController;
use App\Http\Controllers\v1\Author\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/new-admin');
});

Route::get('signIn', [AuthController::class, 'signIn'])->name('admins.signIn');
Route::post('signIn', [AuthController::class, 'AuthSignIn'])->name('admins.AuthSignIn');
Route::get('signOut', [AuthController::class, 'signOut'])->name('admins.signOut');
Route::middleware('admin')->group(function () {
    // admin list apis
    Route::get('admins/list', [AdminController::class, 'list'])->name('admins.list');
    Route::get('admins/add', [AdminController::class, 'add'])->name('admins.add');
    Route::post('admins/add', [AdminController::class, 'store'])->name('admins.store');
    Route::get('admins/edit/{id}', [AdminController::class, 'edit'])->name('admins.edit');
    Route::patch('admin/edit/{id}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('admins/delete/{id}', [AdminController::class, 'delete'])->name('admins.delete');
    Route::get('admins/profile', [AdminController::class, 'user_profile'])->name('admins.profile');
    Route::patch('admin/update-profile', [AdminController::class, 'update_admin_profile'])->middleware("web")->name('profile.update');

    // books routes
    Route::get('books/list', [BookController::class, 'list'])->name('books.list');
    Route::get('books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::patch('books/edit/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('books/delete/{id}', [BookController::class, 'delete'])->name('books.delete');
    Route::post('books/{id}/publish', [BookController::class, 'publish'])->name('books.publish');

    // categories apis
    Route::get('categories/list', [CategoryController::class, 'list'])->name('categories.list');
    Route::get('categories/edit/{category}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('categories/edit/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/delete/{category}', [CategoryController::class, 'delete'])->name('categories.delete');

    //book parts apis
    Route::get('books/{book}/parts', [BookPartController::class, 'list'])->name('books.parts.list');
    Route::post('books/{part}/parts', [BookPartController::class, 'publish'])->name('books.parts.publish');


    // categories apis
    Route::get('ads/list', [AdController::class, 'list'])->name('ads.list');
    Route::get('ads/edit/{ad}', [AdController::class, 'edit'])->name('ads.edit');
    Route::get('ads/create', [AdController::class, 'create'])->name('ads.create');
    Route::post('ads/store', [AdController::class, 'store'])->name('ads.store');
    Route::patch('ads/edit/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('ads/delete/{ad}', [AdController::class, 'delete'])->name('ads.delete');
    Route::get('rtl', function (){
        return view('pages.rtl');
    })->name('rtl');

    Route::get('chat', [ChatController::class, 'index'])->name('chat');
    Route::post('chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::get('chat/get-messages', [ChatController::class, 'getMessages'])->name('chat.get-messages');
    Route::get('/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.get-conversations');
    Route::post('/chat/mark-as-read', [ChatController::class, 'markMessagesAsRead'])->name('chat.mark-as-read');


    // notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('notifications/send', [NotificationController::class, 'sendNotification'])->name('notifications.send');
    Route::get('notifications/notify-user/{user}', [NotificationController::class, 'UserNotification'])->name('notification.user');
    Route::post('notifications/notify-user/{user}', [NotificationController::class, 'SendToUser'])->name('notifications.notify-user');

    //AuthorRequestPayment
    Route::get('requested-payments', [PaymentController::class, 'getRequestedPayment'])->name('requested-payments');
    Route::get('requested-payments/{payment}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('requested-payments/{payment}/approve', [PaymentController::class, 'approve'])->name('requested-payments.approve');
    Route::get('requested-payments/{payment}/reject', [PaymentController::class, 'reject'])->name('requested-payments.reject');

    //subscribers
    Route::get('/subscribers', [SubscriptionController::class, 'index'])->name('subscribers.index');

    //vodafone/instapay
    Route::get('/payment/details', [PaymentController::class, 'payment_details'])->name('payment.details');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update_gateways_details'])->name('payment.update');
    Route::get('payment/manual',[PaymentController::class , 'listManualPayments'])->name('payment.manual');
    Route::post('/manual_subscriptions/store', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::patch('/payments/{payment}/reject', [PaymentController::class, 'reject_manual'])->name('payments.reject');



});
