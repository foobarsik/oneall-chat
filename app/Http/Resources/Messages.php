<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class Messages extends ResourceCollection
{
    public function toArray($request)
    {
       return Message::collection($this->collection);
    }
}
