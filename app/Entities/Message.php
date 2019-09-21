<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $guarded = ['id', 'chat_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

	public function chat()
	{
		return $this->belongsTo(Chat::class);
	}
}
