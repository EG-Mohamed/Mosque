<?php

namespace App\Http\Requests\Api;

class NewsIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'search' => ['sometimes', 'string', 'max:255'],
            'category_id' => ['sometimes', 'integer', 'exists:news_categories,id'],
            'published_from' => ['sometimes', 'date_format:Y-m-d'],
            'published_to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:published_from'],
        ]);
    }
}
