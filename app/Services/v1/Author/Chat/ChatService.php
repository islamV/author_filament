<?php

namespace App\Services\v1\Author\Chat;

use App\Models\ChatRoom;
use App\Models\Message;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatService
{
    use ResponseTrait;
    public $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true,
            ]
        );
    }

    public function sendMessageToAdmin($request)
    {
        $request->validate(["message" => "required|string"]);
        $user = Auth::user();

        $existingConversation = ChatRoom::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', 1)
                ->where('chat_type_id', 1);
        })->first();
        if (!$existingConversation) {
            $existingConversation = ChatRoom::create([
                'receiver_id' => 1,
                'sender_id' => $user->id,
                'chat_type_id' => 1,
            ]);
        }

        $message = Message::create([
            'chat_room_id' => $existingConversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);
        $this->pusher->trigger(
            'chat-room-' . $existingConversation->id . "_chat_type_id_1",
            'message-sent',
            $message
        );
        $this->pusher->trigger(
            'conversations', // Channel name
            'conversation.updated', // Event name
            [
                'conversation' => [
                    'id' => $existingConversation->id, // ID of the conversation
                    'sender_id' => $existingConversation->sender_id,
                    'receiver_id' => $existingConversation->receiver_id,
                    'latest_message' => [
                        'message' => $message->message,
                        'created_at' => $message->created_at,
                    ],
                ],
            ]
        );
        return $message;
    }

    public function getUserConversations($limit)
    {
        $user = Auth::user();
        $followingIds = $user->following()->pluck('users.id');

        $myConversations = ChatRoom::where(function ($query) use ($user, $followingIds) {
            $query->whereIn('sender_id', $followingIds)
                ->where('chat_type_id', 3);
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->orWhere(function ($query) {
                $query->where('id', 20)->where('chat_type_id', 2);
            })
            ->with([
                'sender:id,image,first_name,last_name',
                'receiver:id,image,first_name,last_name',
                'messages' => function ($query) {
                    $query->latest('created_at')->take(1);
                },
                'messages.sender:id,image,first_name,last_name',
            ])
            ->paginate($limit);
    $groupedConversations = $myConversations->getCollection()->groupBy(function ($conversation) {
        switch ($conversation->chat_type_id) {
            case 1:
                return 'admin_chats';
            case 2:
                return 'public_chats';
            case 3:
                return 'author_followers_chats';
            case 4:
                return 'private_chats';
            default:
                return 'other_chats';
        }
    });


        $this->pusher->trigger(
            'conversation_user_id_' . $user->id,
            'conversation.updated',
            $myConversations
        );

        return [
            'myConversations' => $groupedConversations,
            'pagination' => [
                'current_page' => $myConversations->currentPage(),
                'last_page' => $myConversations->lastPage(),
                'per_page' => $myConversations->perPage(),
                'total' => $myConversations->total(),
            ]
        ];
    }



    public function getAdminUserMessage($limit)
    {
        $user = Auth::user();
        return ChatRoom::where("chat_type_id", 1)->where(function ($query) use ($user) {
            $query->where('sender_id', $user->id);
        })->where(function ($query) use ($user,) {
            $query->where('receiver_id', 1);
        })
            ->with(["messages" => function ($query) {
                $query->latest();
            }, "receiver:id,image,first_name,last_name", "sender:id,image,first_name,last_name"])
            ->paginate($limit);
    }

    public function sendToPublic($request)
    {
        $request->validate(["message" => "required|string"]);
        $user = Auth::user();
        $message = Message::create([
            'chat_room_id' => 20,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);
        $this->pusher->trigger(
            "chat-room-20_chat_type_id_2",
            'message-sent',
            $message
        );
        return $message->load(["sender", "chatRoom"]);
    }

    public function getChatPublicMessage($limit)
    {
        return ChatRoom::where("chat_type_id", 2)
            ->where("id", 20)
            ->with(["messages" => function ($query) {
                $query->latest();
            },"messages.sender"])
            ->paginate($limit);
    }

    public function sendMessageToUser($request)
    {
        $request->validate(["message" => "required|string", "user_id" => "required|exists:users,id"]);
        $user = Auth::user();
        $user_receiver = User::findOrFail($request->user_id);
        $existingConversation = ChatRoom::where(function ($query) use ($user, $user_receiver) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $user_receiver->id)
                ->where('chat_type_id', 4);
        })->orWhere(function ($query) use ($user, $user_receiver) {
            $query->where('receiver_id', $user->id)
                ->where('sender_id', $user_receiver->id)
                ->where('chat_type_id', 4);
        })->first();
        if (!$existingConversation) {
            $existingConversation = ChatRoom::create([
                'receiver_id' => $user_receiver->id,
                'sender_id' => $user->id,
                'chat_type_id' => 4,
            ]);
        }

        $message = Message::create([
            'chat_room_id' => $existingConversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);
        $this->pusher->trigger(
            'chat-room-' . $existingConversation->id . "_chat_type_id_4",
            'message-sent',
            $message
        );
        return $message;
    }

    public function getUserMessage($request, $limit)
    {
        $user = Auth::user();
        return ChatRoom::where("chat_type_id", 4)->where(function ($query) use ($user, $request) {
            $query->where('sender_id', $user->id);
            $query->where('receiver_id', $request->user_id);
        })->orWhere(function ($query) use ($user, $request) {
            $query->where('receiver_id', $user->id);
            $query->where('sender_id', $request->user_id);
        })
            ->with(["messages" => function ($query) {
                $query->latest();
            }, "receiver:id,first_name,last_name,image", "sender:id,first_name,last_name,image"])
            ->paginate($limit);
    }

    public function sendToFollower($request)
    {
        $request->validate(["message" => "required|string", "author_id" => "required|exists:users,id"]);
        $user = Auth::user();
        $senderId = $user->role_id == 3 ? $user->id : $request->author_id;
        $existingConversation = ChatRoom::where(function ($query) use ($senderId) {
            $query->where('sender_id', $senderId)
                ->where('chat_type_id', 3);
        })->first();
        if (!$existingConversation) {
            $existingConversation = ChatRoom::create([
                'sender_id' => $senderId,
                'chat_type_id' => 3,
            ]);
        }
        $message = Message::create([
            'chat_room_id' => $existingConversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);
        $this->pusher->trigger(
            'chat-room-' . $existingConversation->id . "_chat_type_id_3",
            'message-sent',
            $message
        );
        return $message;
    }

    public function getFollowerMessage($request, $limit)
    {
        $user = Auth::user();
        $senderId = $user->role_id == 3 ? $user->id : $request->author_id;

        return ChatRoom::where("chat_type_id", 3)
            ->where(function ($query) use ($senderId) {
                $query->where('sender_id', $senderId);
            })
            ->with(["messages" => function ($query) {
                $query->latest();
            },"messages.sender:id,image,first_name,last_name"])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

    }
}
