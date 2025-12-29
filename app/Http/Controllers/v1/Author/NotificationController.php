<?php

namespace App\Http\Controllers\v1\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SendNotificationsRequest;
use App\Models\Book;
use App\Models\UserNotification;
use App\Models\User;
use App\Services\v1\Author\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// Assuming you have a User model

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        return view('pages.notifications.notifications');
    }

    public function sendNotification(SendNotificationsRequest $request)
    {
        try {
            ini_set('max_execution_time', 30000); // Increase execution time if needed
            $startTime = microtime(true);
            DB::beginTransaction();

            // Determine which roles to notify
            $roles = [];
            if ($request->has('notify_authors')) {
                $roles = array_merge($roles, [2, 3]);
            }
            if ($request->has('notify_users')) {
                $roles[] = 4;
            }

            $usersQuery = User::whereIn('role_id', $roles)
                ->whereNotNull('device_token')
                ->where('device_token', '!=', '');

            $usersQuery->chunk(5000, function ($usersChunk) use ($request) {
                $deviceTokens = $usersChunk->pluck('device_token')->toArray();

                // Send notification via Firebase
                if (!empty($deviceTokens)) {
                    $this->firebaseService->sendMulticastNotification(
                        $deviceTokens,
                        $request->title,
                        $request->body,
                        ['additional' => 'data']
                    );
                }
                

                // Create notification
                $notification = UserNotification::create([
                    'title' => $request->title,
                    'content' => $request->body,
                ]);

                // Attach users to the notification
                $notification->users()->attach($usersChunk->pluck('id')->toArray(), [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            DB::commit();
            $executionTime = microtime(true) - $startTime;
            Log::info("Notification function executed in {$executionTime} seconds");

            return back()->with('status', 'Notifications sent and stored successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', 'Failed to send notifications');
        }
    }



    public function UserNotification(User $user)
    {
        return view('pages.notifications.user-notification', compact('user'));
    }

    public function SendToUser(User $user, Request $request)
    {
        try {
            DB::beginTransaction();

            $deviceToken = $user->device_token;

            if (empty($deviceToken)) {
                return back()->with('error', 'No valid device token found');
            }

            $this->firebaseService->sendNotification(
                $deviceToken,
                $request->title,
                $request->body,
                ['additional' => 'data']
            );

            // Create notification and attach user
            $notification = UserNotification::create([
                'title' => $request->title,
                'content' => $request->body,
            ]);

            $notification->users()->attach($user->id, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return back()->with('status', 'Notification sent successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', 'Failed to send notification');
        }
    }

}
