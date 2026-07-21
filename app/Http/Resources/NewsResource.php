<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class NewsResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translated('title'),
            'slug' => $this->slug,
            'excerpt' => $this->translated('excerpt'),
            'content' => $this->translated('content'),
            'featured_image_url' => $this->assetUrl($this->featured_image),
            'published_at' => $this->dateTimeString($this->published_at),
            'url' => route('news.show', $this->slug),
            'categories' => NewsCategoryResource::collection($this->whenLoaded('categories')),
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
