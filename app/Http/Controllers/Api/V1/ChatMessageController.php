<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\Chat;
use App\Entities\Message;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\Chat as ChatResource;
use App\Events\MessageSent;
use App\Factories\MessengerFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function index($chatId)
    {
        $messages = Message::where('chat_id', $chatId)->latest()->paginate(20);

        return new \App\Http\Resources\Messages($messages);
    }

    public function store($chatId, Request $request)
    {
        $request->validate(['text' => 'required|string']);

        $chat = Chat::findOrFail($chatId);
        $messenger = MessengerFactory::create($chat->bot->type, $chat->bot->token);
        $messenger->sendMessage($chat->external_id, $request->text);
        $message = $chat->messages()->create($request->all());
        broadcast(new MessageSent(new MessageResource($message), new ChatResource($chat)));

        return new MessageResource($message);
    }
}
