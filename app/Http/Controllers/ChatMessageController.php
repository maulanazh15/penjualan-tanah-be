<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Events\NewMessageSent;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request)
    {
        $data = $request->validated();

        $chatId = $data['chat_id'];
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $messages = ChatMessage::where('chat_id', $chatId)
            ->with('user')
            ->latest('created_at')
            ->simplePaginate(
                $pageSize,
                ['*'],
                'page',
                $currentPage,
            );

        return $this->success($messages->getCollection());
    }

    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->load('user');
        $chatMessage->load('chat');

        // TODO send broadcast event to pusher and send notifications to onesignal services
        $this->sendNotificationToOther($chatMessage);
        return $this->success($chatMessage, 'Message has been sent succesfully');
    }

    private function sendNotificationToOther(ChatMessage $chatMessage)
    {
        $chatId = $chatMessage->chat_id;

        // NewMessageSent::dispatch($chatMessage);
        
        broadcast(new NewMessageSent($chatMessage))->toOthers();

        $user = auth()->user();
        $userId = $user->id;

        $chat = Chat::where('id', $chatId)
            ->with(['participants' => function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId);
            }])->first();

        if (count($chat->participants) > 0) {
            $otherUserId = $chat->participants[0]->user_id;

            $otherUser = User::where('id', $otherUserId)->first();
            // dd($otherUser);
            $otherUser->sendNewMessageNotification([
                'messageData' => [
                    'senderName' => $user->name,
                    'message' => $chatMessage->message,
                    'chatId' => $chatMessage->chat_id
                ]
            ]);
            
        }
    }
}
