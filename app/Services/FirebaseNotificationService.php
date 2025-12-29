<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\UserNotification;
use Google_Client as GoogleClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client as GuzzleClient;

class FirebaseNotificationService
{
    protected $credentialsPath;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase/firebase_credentials.json');
    }

    /**
     * إرسال إشعار لمستخدمين محددين أو للجميع
     */
    public function sendCustomNotification(?string $title, string $message, array $userIds = [], bool $sendToAll = false, array $additionalData = []): array
    {
        $failedUsers = [];
        $sentCount = 0;

        try {
            // الحصول على المستخدمين
            if ($sendToAll) {
                $users = User::whereNotNull('device_token')
                            ->where('device_token', '!=', '')
                            ->get();
            } elseif (!empty($userIds)) {
                $users = User::whereIn('id', $userIds)
                            ->whereNotNull('device_token')
                            ->where('device_token', '!=', '')
                            ->get();
            } else {
                throw new Exception(__('notifications.no_recipients'));
            }

            if ($users->isEmpty()) {
                return [
                    'success' => false,
                    'failed_users' => [['error' => __('notifications.no_users_found')]],
                    'sent_count' => 0,
                    'message' => __('notifications.no_users_found'),
                ];
            }

            // إنشاء إشعار واحد في الجدول الرئيسي
            $notification = UserNotification::create([
                'title' => $title ?? __('notifications.default_title'),
                'content' => $message,
            ]);

            // ربط الإشعار بالمستخدمين في الجدول الوسيط
            $pivotData = [];
            foreach ($users as $user) {
                $pivotData[$user->id] = [
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $notification->users()->attach($pivotData);

            // إرسال الإشعارات عبر Firebase
            foreach ($users as $user) {
                try {
                    if ($user->device_token) {
                        $this->sendToDevice(
                            $user->device_token,
                            $title ?? __('notifications.default_title'),
                            $message,
                            $additionalData
                        );
                        $sentCount++;
                    } else {
                        $failedUsers[] = [
                            'id' => $user->id,
                            'name' => $user->name ?? "User #{$user->id}",
                            'error' => __('notifications.no_device_token')
                        ];
                    }
                } catch (Exception $e) {
                    $failedUsers[] = [
                        'id' => $user->id,
                        'name' => $user->name ?? "User #{$user->id}",
                        'error' => $e->getMessage()
                    ];
                    Log::warning(__('notifications.failed_user_log', ['id' => $user->id, 'error' => $e->getMessage()]));
                }
            }

            return [
                'success' => count($failedUsers) === 0,
                'failed_users' => $failedUsers,
                'sent_count' => $sentCount,
                'notification_id' => $notification->id,
                'total_users' => $users->count(),
                'message' => count($failedUsers) === 0
                    ? __('notifications.all_sent_success')
                    : __('notifications.some_failed', [
                        'success' => $sentCount, 
                        'failed' => count($failedUsers)
                    ]),
            ];

        } catch (Exception $e) {
            Log::error(__('notifications.firebase_error') . $e->getMessage());
            return [
                'success' => false,
                'failed_users' => [['error' => 'System error: ' . $e->getMessage()]],
                'sent_count' => $sentCount,
                'message' => __('notifications.system_error'),
            ];
        }
    }

    /**
     * إرسال إشعار لأدوار محددة
     */
    public function sendNotificationToRoles(?string $title, string $message, array $roleIds, array $additionalData = []): array
    {
        $failedUsers = [];
        $sentCount = 0;

        try {
            // الحصول على المستخدمين حسب الأدوار
            $users = User::whereIn('role_id', $roleIds)
                        ->whereNotNull('device_token')
                        ->where('device_token', '!=', '')
                        ->get();

            if ($users->isEmpty()) {
                return [
                    'success' => false,
                    'failed_users' => [],
                    'sent_count' => 0,
                    'message' => __('notifications.no_users_found'),
                ];
            }

            // إنشاء إشعار واحد في الجدول الرئيسي
            $notification = UserNotification::create([
                'title' => $title ?? __('notifications.default_title'),
                'content' => $message,
            ]);

            // ربط الإشعار بالمستخدمين في الجدول الوسيط
            $pivotData = [];
            foreach ($users as $user) {
                $pivotData[$user->id] = [
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $notification->users()->attach($pivotData);

            // إرسال الإشعارات عبر Firebase
            foreach ($users as $user) {
                try {
                    $this->sendToDevice(
                        $user->device_token,
                        $title ?? __('notifications.default_title'),
                        $message,
                        $additionalData
                    );
                    $sentCount++;
                } catch (Exception $e) {
                    $failedUsers[] = [
                        'id' => $user->id,
                        'name' => $user->name ?? "User #{$user->id}",
                        'error' => $e->getMessage()
                    ];
                    Log::warning(__('notifications.failed_user_log', ['id' => $user->id, 'error' => $e->getMessage()]));
                }
            }

            return [
                'success' => count($failedUsers) === 0,
                'failed_users' => $failedUsers,
                'sent_count' => $sentCount,
                'notification_id' => $notification->id,
                'total_users' => $users->count(),
                'message' => count($failedUsers) === 0
                    ? __('notifications.all_sent_success')
                    : __('notifications.some_failed', [
                        'success' => $sentCount, 
                        'failed' => count($failedUsers)
                    ]),
            ];

        } catch (Exception $e) {
            Log::error(__('notifications.firebase_error') . $e->getMessage());
            return [
                'success' => false,
                'failed_users' => [['error' => 'System error: ' . $e->getMessage()]],
                'sent_count' => $sentCount,
                'message' => __('notifications.system_error'),
            ];
        }
    }

    /**
     * إرسال إشعار لمستخدم واحد (للتطبيق القديم)
     */
    public function sendToSingleUser(?string $title, string $message, string $fcmToken, array $additionalData = []): array
    {
        try {
            $user = User::where('device_token', $fcmToken)->first();
            $userIds = $user ? [$user->id] : [];
            
            return $this->sendCustomNotification($title, $message, $userIds, false, $additionalData);
            
        } catch (Exception $e) {
            Log::error(__('notifications.firebase_error') . $e->getMessage());
            return [
                'success' => false,
                'failed_users' => [['error' => 'System error: ' . $e->getMessage()]],
                'sent_count' => 0,
                'message' => __('notifications.system_error'),
            ];
        }
    }

    /**
     * إرسال إشعار لجهاز واحد
     */
    private function sendToDevice(string $fcmToken, string $title, string $body, array $additionalData = [])
    {
        if (!file_exists($this->credentialsPath)) {
            throw new Exception(__('notifications.credentials_missing'));
        }

        try {
            $client = new GoogleClient();
            $client->setAuthConfig($this->credentialsPath);
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->fetchAccessTokenWithAssertion();
            $accessToken = $client->getAccessToken();

            if (empty($accessToken['access_token'])) {
                throw new Exception(__('notifications.token_missing'));
            }

            $fcmData = [
                'type' => 'admin_notification',
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'title' => (string) $title,    
                'body' => (string) $body,      
            ];

            if (!empty($additionalData)) {
                foreach ($additionalData as $key => $value) {
                    $fcmData[$key] = (string) $value; 
                }
            }

            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $fcmData,
                    'android' => [
                        'priority' => 'high',
                    ],
                    'apns' => [
                        'payload' => [
                            'aps' => [
                                'content-available' => 1,
                            ],
                        ],
                        'headers' => [
                            'apns-priority' => '5',
                        ],
                    ],
                ],
            ];

            $projectId = env('FIREBASE_PROJECT_ID');

            $httpClient = new GuzzleClient();
            $response = $httpClient->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken['access_token'],
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => 10,
            ]);

            Log::info(__('notifications.sent_success', ['token' => substr($fcmToken, 0, 10) . '...']));

        } catch (Exception $e) {
            Log::error(__('notifications.sending_error') . $e->getMessage(), [
                'token' => substr($fcmToken, 0, 10) . '...',
                'error_trace' => $e->getTraceAsString()
            ]);
            throw new Exception(__('notifications.device_send_failed') . ': ' . $e->getMessage());
        }
    }
}