<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    public const TYPE_TELEGRAM = 'telegram';
    public const TYPE_VIBER = 'viber';
    public const TYPES = [self::TYPE_TELEGRAM, self::TYPE_VIBER];

    protected $guarded = ['id'];
}
