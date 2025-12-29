<?php

namespace App\Http\Controllers\v1\Author\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class ChatController extends Controller
{
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

    public function index()
    {
        return view('pages.users.chat');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'chat_room_id' => 'required|exists:chat_rooms,id',
        ]);

        $sender_id = Auth::id();
        $message = Message::create([
            'message' => $request->message,
            'sender_id' => $sender_id,
            'chat_room_id' => $request->chat_room_id,
        ]);
        $messageData = $message->load('sender:id,first_name');
        $this->pusher->trigger(
            'chat-room-' . $request->chat_room_id . "_chat_type_id_1",
            'message-sent',
            $messageData
        );

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function getMessages(Request $request)
    {
        $messages = Message::where('chat_room_id', $request->chatRoomId)
            ->with('sender:id,first_name')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function getConversations()
    {
        $userId = Auth::id();

        $conversations = ChatRoom::where('chat_type_id', 1)
            ->with([
                'sender',
                'receiver',
                'latestMessage',
                'chatType',
            ])
            ->get()
            ->map(function ($conversation) use ($userId) {
                // Get the count of unread messages where the user is NOT the sender
                $conversation->unread_messages_count = Message::where('chat_room_id', $conversation->id)
                    ->where('sender_id', '!=', $userId)
                    ->where('status', 0) // Unread messages
                    ->count();
                return $conversation;
            });

        return response()->json($conversations);
    }

    public function markMessagesAsRead(Request $request)
    {
        $request->validate([
            'chat_room_id' => 'required|exists:chat_rooms,id',
        ]);

        Message::where('chat_room_id', $request->chat_room_id)
            ->where('sender_id', '!=', Auth::id())
            ->where('status', 0)
            ->update(['status' => 1]);

        return response()->json(['message' => 'Messages marked as read']);
    }


}
