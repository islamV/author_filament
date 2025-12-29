<?php

namespace App\Http\Controllers\v1\Author\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Author\Chat\ChatResource;
use App\Http\Resources\v1\Author\Chat\ConversationResource;
use App\Http\Resources\v1\Author\Chat\MessageResource;
use App\Http\Resources\v1\Author\Chat\PublicChatResource;
use App\Http\Resources\v1\Author\Chat\PublicMessageResource;
use App\Services\v1\Author\Chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private ChatService $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }
    public function sendMessageToAdmin(Request $request)
    {
        return $this->returnData(__('messages.chat_send'),200,
            new ChatResource($this->chatService->sendMessageToAdmin($request)));
    }

    public function getAdminUserMessage(Request $request)
    {
        return $this->returnData(__('messages.get_messages'),200,
            /*MessageResource::collection*/($this->chatService->getAdminUserMessage($request->get("per_page",10))));
    }

    public function sendToPublic(Request $request)
    {
        return $this->returnData(__('messages.chat_send'),200,
            new PublicChatResource($this->chatService->sendToPublic($request)));
    }
    public function getChatPublicMessage(Request $request)
    {
        return $this->returnData(__('messages.get_messages'),200,
            PublicMessageResource::collection($this->chatService->getChatPublicMessage($request->get("per_page",10))));
    }

    public function sendMessageToUser(Request $request)
    {
        return $this->returnData(__('messages.chat_send'),200,
            new ChatResource($this->chatService->sendMessageToUser($request)));
    }

    public function getUserMessage(Request $request)
    {
        return $this->returnData(__('messages.get_messages'),200,
            /*MessageResource::collection*/($this->chatService->getUserMessage($request,$request->get("per_page",10))));
    }

    public function sendToFollower(Request $request)
    {
        return $this->returnData(__('messages.chat_send'),200,
            /*new ChatResource*/($this->chatService->sendToFollower($request)));
    }

    public function getFollowerMessage(Request $request)
    {
        return $this->returnData(__('messages.get_messages'),200,
            ($this->chatService->getFollowerMessage($request,$request->get("per_page",10))));
    }

    public function getUserConversations(Request $request)
    {
        return $this->returnData(__('messages.get_messages'),200,$this->chatService->getUserConversations($request->get("per_page",10)));
    }

}
