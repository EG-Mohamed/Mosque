<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class SpecialPrayerResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->translated('name', $request),
            'group' => $this->translated('group', $request),
            'description' => $this->translated('description', $request),
            'location' => $this->location,
            'date' => $this->dateString($this->date),
            'time' => $this->time,
            'end_time' => $this->end_time,
            'type' => $this->enumValue($this->type),
            'is_recurring' => $this->is_recurring,
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
