<?php

namespace App\Http\Requests\Api;

use App\Enums\AnnouncementType;
use Illuminate\Validation\Rule;

class AnnouncementIndexRequest extends PaginatedApiRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'type' => ['sometimes', Rule::enum(AnnouncementType::class)],
        ]);
    }
}
