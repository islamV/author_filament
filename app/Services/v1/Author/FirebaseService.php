<?php

namespace App\Services\v1\Author;

use Google\Client;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected $firebase;
    protected $messaging;
    private $accessToken;
    private $projectId;

    public function __construct()
    {
        // Initialize Firebase Admin SDK
        $this->firebase = (new Factory)
            ->withServiceAccount(storage_path("app/firebase/hiber-test-fa801-firebase-adminsdk-ebup1-46840d6018.json"));
        $this->messaging = $this->firebase->createMessaging();

        // Initialize HTTP v1 API
        $this->projectId = config('services.firebase.project_id');
        $this->initializeGoogleClient();
    }

    private function initializeGoogleClient()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/firebase/hiber-test-fa801-firebase-adminsdk-ebup1-46840d6018.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $this->accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];
    }

    public function sendNotification($token, $title, $body, $data = [])
    {
        try {
            // Try HTTP v1 API first
            return $this->sendNotificationV1($token, $title, $body, $data);
        } catch (\Exception $e) {
            // Fallback to Admin SDK
            return $this->sendNotificationLegacy($token, $title, $body, $data);
        }
    }

    private function sendNotificationV1($deviceToken, $title, $body, $data = [])
    {
        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $message = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data
            ]
        ];

        $response = Http::withToken($this->accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $message);

        return $response->json();
    }

    private function sendNotificationLegacy($token, $title, $body, $data = [])
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($data);
        return $this->messaging->send($message);
    }

    public function sendMulticastNotification($tokens, $title, $body, $data = [])
    {
        try {
            $responses = [];
            foreach ($tokens as $token) {
                $responses[] = $this->sendNotificationV1($token, $title, $body, $data);
            }
            return $responses;
        } catch (\Exception $e) {
            // Fallback to Admin SDK
            $notification = Notification::create($title, $body);
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);
            return $this->messaging->sendMulticast($message, $tokens);
        }
    }
}
