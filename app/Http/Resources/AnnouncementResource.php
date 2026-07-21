<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AnnouncementResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translated('title'),
            'content' => $this->translated('content'),
            'type' => $this->enumValue($this->type),
            'published_at' => $this->dateTimeString($this->published_at),
            'expires_at' => $this->dateTimeString($this->expires_at),
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
