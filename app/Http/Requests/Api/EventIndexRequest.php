<?php

namespace App\Http\Requests\Api;

class EventIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:from'],
            'featured' => ['sometimes', 'boolean'],
        ]);
    }
}
