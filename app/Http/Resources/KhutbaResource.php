<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class KhutbaResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translated('title', $request),
            'slug' => $this->slug,
            'topic' => $this->translated('topic', $request),
            'summary' => $this->translated('summary', $request),
            'speaker' => $this->translated('speaker', $request),
            'content' => $this->translated('content', $request),
            'date' => $this->dateString($this->date),
            'audio_url' => $this->assetUrl($this->audio_url),
            'video_url' => $this->assetUrl($this->video_url),
            'featured_image_url' => $this->assetUrl($this->featured_image),
            'url' => filled($this->slug) ? route('khutba.show', $this->slug) : null,
            'categories' => KhutbaCategoryResource::collection($this->whenLoaded('categories')),
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
