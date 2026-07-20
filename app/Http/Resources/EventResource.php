<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class EventResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translated('title', $request),
            'description' => $this->translated('description', $request),
            'location' => $this->translated('location', $request),
            'image' => $this->image,
            'image_url' => $this->assetUrl($this->image),
            'starts_at' => $this->dateTimeString($this->starts_at),
            'ends_at' => $this->dateTimeString($this->ends_at),
            'status' => $this->enumValue($this->status),
            'is_featured' => $this->is_featured,
            'url' => route('events.show', $this->resource),
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
