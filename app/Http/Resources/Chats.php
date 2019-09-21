<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Chats extends ResourceCollection
{
    public function toArray($request)
    {
       return Chat::collection($this->collection);
    }
}
