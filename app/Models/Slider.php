<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slider extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'subtitle', 'button_text'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'media_type' => MediaType::class,
        ];
    }

    public function isVideo(): bool
    {
        return $this->media_type === MediaType::Video;
    }

    public function scopeActive($query): void
    {
        $query->where('is_active', true)->orderBy('sort_order');
    }
}
