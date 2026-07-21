<?php

namespace App\Http\Resources;

use App\Support\AssetPath;
use BackedEnum;
use DateTimeInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResource extends JsonResource
{
    protected function translated(string $attribute): ?string
    {
        $value = method_exists($this->resource, 'getTranslation')
            ? $this->resource->getTranslation($attribute, app()->getLocale(), true)
            : $this->{$attribute};

        return filled($value) ? (string) $value : null;
    }

    protected function assetUrl(?string $path): ?string
    {
        return AssetPath::url($path);
    }

    protected function dateString(?DateTimeInterface $date): ?string
    {
        return $date?->format('Y-m-d');
    }

    protected function dateTimeString(?DateTimeInterface $date): ?string
    {
        return $date?->format(DateTimeInterface::ATOM);
    }

    protected function enumValue(mixed $value): mixed
    {
        return $value instanceof BackedEnum ? $value->value : $value;
    }
}
