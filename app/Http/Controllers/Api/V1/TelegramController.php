<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\TelegramMessage;
use App\Http\Controllers\Controller;
use App\Services\MessageHandler;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    private $messageHandler;

    public function __construct(MessageHandler $messageHandler)
    {
        $this->messageHandler = $messageHandler;
    }

    public function handleCallback($token, Request $request)
    {
        if ($request['message']) {
            $this->messageHandler->handle(new TelegramMessage($request['message']), $token);
        }
        return 'OK';
    }
}
