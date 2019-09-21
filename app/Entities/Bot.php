<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
    use SoftDeletes;

    public const TYPE_TELEGRAM = 'telegram';
    public const TYPE_VIBER = 'viber';
    public const TYPES = [self::TYPE_TELEGRAM, self::TYPE_VIBER];

    protected $guarded = ['id'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
