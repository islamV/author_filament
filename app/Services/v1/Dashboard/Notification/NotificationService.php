<?php

namespace App\Services\v1\Dashboard\Notification;

use App\Models\Book;
use App\Models\UserNotification;
use App\Models\User;
use App\Repositories\v1\Interface\Dashboard\Ad\IAd;
use App\Services\v1\Author\FirebaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $firebaseService;
    

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendNotificationToFollowers(User $author, Book $book)
    {
        try {
            DB::beginTransaction();

            $followers = $author->followedBy()->whereNotNull('device_token')->where('device_token', '!=', '')->get();
            $deviceTokens = $followers->pluck('device_token')->toArray();
            if (!empty($deviceTokens)) {
                $response = $this->firebaseService->sendMulticastNotification(
                    $deviceTokens,
                    "New Book Published",
                    "{$author->first_name } {$author->last_name} just published a new book: {$book->title}",
                    ['additional' => 'data']
                );

                Log::info('Firebase response:', ['response' => $response]);
            }


            $notification = UserNotification::create([
                'title' => "New Book Published",
                'content' => "{$author->first_name } {$author->last_name} just published a new book: {$book->title}",
            ]);

            $notification->users()->attach($followers->pluck('id')->toArray(), [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
    }

}
