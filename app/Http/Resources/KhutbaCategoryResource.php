<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class KhutbaCategoryResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name', $request),
            'sort_order' => $this->sort_order,
        ];
    }
}
