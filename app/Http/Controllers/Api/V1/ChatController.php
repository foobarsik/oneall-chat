<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\Chat;
use \App\Http\Resources\Chats;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return new Chats(Chat::latest()->get());
    }
}
