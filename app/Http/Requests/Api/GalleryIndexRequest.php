<?php

namespace App\Http\Requests\Api;

use App\Enums\MediaType;
use Illuminate\Validation\Rule;

class GalleryIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'collection' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::enum(MediaType::class)],
        ]);
    }
}
