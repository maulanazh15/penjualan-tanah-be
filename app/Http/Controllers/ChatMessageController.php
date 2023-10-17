<?php

namespace App\Http\Controllers;

use App\Events\NewMessageSent;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use App\Http\Requests\GetMessageRequest;
use App\Http\Requests\StoreMessageRequest;

class ChatMessageController extends Controller
{
    public function index(GetMessageRequest $request) {
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

    public function store(StoreMessageRequest $request) {
        $data = $request->validated();

        $data['user_id'] = auth()->user()->id;

        $chatMessage = ChatMessage::create($data);
        $chatMessage->load('user');
        $chatMessage->load('chat');

        // TODO send broadcast event to pusher and send notifications to onesignal services
        $this->sendNotificationToOther($chatMessage);
        return $this->success($chatMessage, 'Message has been sent succesfully');
    }

    private function sendNotificationToOther(ChatMessage $chatMessage) {
        // $chatId = $chatMessage->chat_id;

        NewMessageSent::dispatch($chatMessage);
    }
}
