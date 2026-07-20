<?php

namespace App\Http\Requests\Api;

class KhutbaIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'integer', 'exists:khutba_categories,id'],
            'date' => ['sometimes', 'date_format:Y-m-d'],
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);
    }
}
