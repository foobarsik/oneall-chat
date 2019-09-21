<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ViberMessage;
use App\Entities\Bot;
use App\Entities\Chat;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chat as ChatResource;
use App\Http\Resources\Message as MessageResource;
use Illuminate\Http\Request;

class ViberController extends Controller
{
    public function handleCallback($token, Request $request)
    {
        if ($request['event'] === 'message') {
            $bot = Bot::where('token', $token)->latest()->firstOrFail();
            $viberMessage = new ViberMessage($request);
            $chat = Chat::firstOrCreate(
                ['external_id' => $viberMessage->senderId()],
                ['name' => $viberMessage->senderName(), 'bot_id' => $bot->id]
            );
            $message = $chat->messages()->create([
                'text' => $viberMessage->text(),
                'external_id' => $viberMessage->id()
            ]);
            broadcast(new MessageSent(new MessageResource($message), new ChatResource($chat)));
        }
        return 'OK';
    }
}
