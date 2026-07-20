<?php

namespace App\Http\Requests\Api;

class PrayerTimeIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'date' => ['sometimes', 'date_format:Y-m-d'],
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:from'],
            'year' => ['sometimes', 'integer', 'min:1900', 'max:2200'],
            'month' => ['sometimes', 'integer', 'min:1', 'max:12'],
        ]);
    }
}
