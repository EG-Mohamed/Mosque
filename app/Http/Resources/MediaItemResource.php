<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class MediaItemResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->translated('title', $request),
            'alt_text' => $this->translated('alt_text', $request),
            'file_url' => $this->assetUrl($this->file_path),
            'type' => $this->enumValue($this->type),
            'collection' => $this->collection,
            'sort_order' => $this->sort_order,
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
