<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class NewsCategoryResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name'),
            'parent_id' => $this->parent_id,
            'sort_order' => $this->sort_order,
        ];
    }
}
