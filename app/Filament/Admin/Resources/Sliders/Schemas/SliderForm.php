<?php

namespace App\Filament\Admin\Resources\Sliders\Schemas;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Enums\MediaType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Slide Content'))
                    ->schema([
                        Select::make('media_type')
                            ->label(__('Media Type'))
                            ->options(MediaType::class)
                            ->default(MediaType::Image)
                            ->required()
                            ->live()
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label(__('Image/Video'))
                            ->directory('sliders')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TranslatableTabs::make()
                            ->schema([
                                TextInput::make('title')->label(__('Title')),
                                TextInput::make('subtitle')->label(__('Subtitle')),
                            ]),
                    ]),

                Section::make(__('Button'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('button_text')
                            ->translatableTabs()
                            ->columnSpanFull()
                            ->label(__('Button Text')),
                        TextInput::make('button_url')
                            ->label(__('Button URL'))
                            ->url(),
                    ]),

                Section::make(__('Settings'))
                    ->aside()
                    ->schema([
                        ToggleButtons::make('is_active')
                            ->inline()
                            ->boolean()
                            ->label(__('Active'))
                            ->default(true),
                    ]),
            ]);
    }
}
