<?php

namespace App\Dto;

use App\Contracts\Message;

class TelegramMessage implements Message
{
    private $id;
    private $text;
    private $senderId;
    private $senderName;

    public function __construct($message)
    {
        $this->id = $message['message_id'];
        $this->text = $message['text'] ?? '[file]';
        $this->senderId = $message['chat']['id'];
        $this->senderName = trim($message['chat']['first_name'] ?? '' . ' ' . $message['chat']['last_name'] ?? '');
    }

    public function id()
    {
        return $this->id;
    }

    public function text()
    {
        return $this->text;
    }

    public function senderId()
    {
        return $this->senderId;
    }

    public function senderName()
    {
        return $this->senderName;
    }
}
