<?php

namespace App\Factories;

use App\Contracts\Messenger;
use App\Entities\Bot;
use App\Services\Telegram\Client as Telegram;
use App\Services\Viber\Client as Viber;

class MessengerFactory
{
    public static function create(string $type, string $token): Messenger
    {
        if ($type === Bot::TYPE_TELEGRAM) {
            return new Telegram($token);
        }
        if ($type === Bot::TYPE_VIBER) {
            return new Viber($token);
        }

        throw new \DomainException('Unknown bot type: ' . $type);
    }
}
