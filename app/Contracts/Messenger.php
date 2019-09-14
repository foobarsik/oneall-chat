<?php

namespace App\Contracts;

interface Messenger
{
    public function setWebhook(string $url);

    public function deleteWebhook();

    public function getProfile();

    public function sendMessage($receiverId, $text);
}
