<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use function Baethon\Laravel\Resource\resource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'tags' => resource($this->whenLoaded('tags')),
        ];
    }
}
