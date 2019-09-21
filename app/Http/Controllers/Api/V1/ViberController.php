<?php

namespace App\Http\Controllers\Api\V1;

use App\Dto\ViberMessage;
use App\Http\Controllers\Controller;
use App\Services\MessageHandler;
use Illuminate\Http\Request;

class ViberController extends Controller
{
    private $messageHandler;

    public function __construct(MessageHandler $messageHandler)
    {
        $this->messageHandler = $messageHandler;
    }

    public function handleCallback($token, Request $request)
    {
        if ($request['event'] === 'message') {
            $this->messageHandler->handle(new ViberMessage($request), $token);
        }
        return 'OK';
    }
}
