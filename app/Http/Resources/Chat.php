<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Chat extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'bot' => $this->bot->name,
            'messenger' => $this->bot->type,
            'lastMessage' => new Message($this->messages()->latest()->first()),
            'isNew' => $this->wasRecentlyCreated
        ];
    }
}
