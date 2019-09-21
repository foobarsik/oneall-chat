<?php

namespace App\Services;

use App\Contracts\Message;
use App\Entities\Bot;
use App\Entities\Chat;
use App\Events\MessageSent;
use App\Http\Resources\Chat as ChatResource;
use App\Http\Resources\Message as MessageResource;

class MessageHandler
{
    public function handle(Message $incomingMessage, $botToken)
    {
        $bot = Bot::where('token', $botToken)->latest()->firstOrFail();
        $chat = Chat::firstOrCreate(
            ['external_id' => $incomingMessage->senderId()],
            ['name' => $incomingMessage->senderName(), 'bot_id' => $bot->id]
        );
        $message = $chat->messages()->create([
            'text' => $incomingMessage->text(),
            'external_id' => $incomingMessage->id()
        ]);
        broadcast(new MessageSent(new MessageResource($message), new ChatResource($chat)));
    }
}
