<?php

namespace App\Contracts;

interface Message
{
    public function id();

    public function text();

    public function senderId();

    public function senderName();
}
