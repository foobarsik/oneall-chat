<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
	protected $guarded = ['id'];

	public function messages()
	{
		return $this->hasMany(Message::class);
	}

	public function bot()
	{
		return $this->belongsTo(Bot::class)->withTrashed();
	}
}
