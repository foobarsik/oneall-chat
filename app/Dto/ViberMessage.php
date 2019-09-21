<?php

namespace App\Dto;

use App\Contracts\Message;

class ViberMessage implements Message
{
    private $id;
    private $text;
    private $senderId;
    private $senderName;

    public function __construct($message)
    {
        $this->id = $message['message_token'];
        $this->text = $message['message']['text'] ?? '[file]';
        $this->senderId = $message['sender']['id'];
        $this->senderName = $message['sender']['name'] ?? '';
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
