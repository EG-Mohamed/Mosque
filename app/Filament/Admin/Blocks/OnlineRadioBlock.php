<?php

namespace App\Filament\Admin\Blocks;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\BlockCategories\Worship;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Redberry\PageBuilderPlugin\Abstracts\BaseBlock;

class OnlineRadioBlock extends BaseBlock
{
    public static function getBlockSchema(): array
    {
        return [
            TranslatableTabs::make()
                ->schema([
                    TextInput::make('title')
                        ->label(__('Section Title'))
                        ->default(__('Online Quran Radio')),
                    TextInput::make('intro')
                        ->label(__('Description')),
                ]),
            Select::make('language_mode')
                ->label(__('API Language'))
                ->options([
                    'auto' => __('Auto'),
                    'ar' => __('Arabic'),
                    'eng' => __('English'),
                ])
                ->default('auto'),
        ];
    }

    public static function formatForSingleView(array $data): array
    {
        $locale = app()->getLocale();

        foreach (['title', 'intro'] as $field) {
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $data[$field][$locale] ?? collect($data[$field])->first(fn ($value) => filled($value)) ?? '';
            }
        }

        return $data;
    }

    public static function getCategory(): string
    {
        return Worship::class;
    }

    public static function getBlockLabel(array $state, ?int $index = null): mixed
    {
        return (data_get($state, 'order') + 1).' - '.class_basename(data_get($state, 'block_type'));
    }

    public static function getThumbnail(): string|Htmlable|null
    {
        return asset('images/blocks/'.class_basename(self::class).'.svg');
    }

    public static function getView(): ?string
    {
        return 'components.blocks.online-radio';
    }
}
