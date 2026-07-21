<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class StaffResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name'),
            'title' => $this->translated('title'),
            'bio' => $this->translated('bio'),
            'photo' => $this->photo,
            'photo_url' => $this->assetUrl($this->photo),
            'email' => $this->email,
            'phone' => $this->phone,
            'sort_order' => $this->sort_order,
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
