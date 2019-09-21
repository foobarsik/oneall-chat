<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\TelegramMessage;
use App\Entities\Bot;
use App\Entities\Chat;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\Chat as ChatResource;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function handleCallback($token, Request $request)
    {
        if ($request['message']) {
            $bot = Bot::where('token', $token)->latest()->firstOrFail();
            $telegramMessage = new TelegramMessage($request['message']);
            $chat = Chat::firstOrCreate(
                ['external_id' => $telegramMessage->senderId()],
                ['name' => $telegramMessage->senderName(), 'bot_id' => $bot->id]
            );
            $message = $chat->messages()->create([
                'text' => $telegramMessage->text(),
                'external_id' => $telegramMessage->id()
            ]);
            broadcast(new MessageSent(new MessageResource($message), new ChatResource($chat)));
        }
        return 'OK';
    }
}
