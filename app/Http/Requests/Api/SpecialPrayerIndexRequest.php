<?php

namespace App\Http\Requests\Api;

use App\Enums\SpecialPrayerType;
use Illuminate\Validation\Rule;

class SpecialPrayerIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'date' => ['sometimes', 'date_format:Y-m-d'],
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:from'],
            'type' => ['sometimes', Rule::enum(SpecialPrayerType::class)],
        ]);
    }
}
