<?php

namespace App\Filament\Admin\Pages;

use App\Enums\CalculationMethod;
use App\Enums\PrayerMethod;
use App\Support\PublicNavigation;
use Asmit\FilamentUpload\Enums\PdfViewFit;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use BackedEnum;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Outerweb\FilamentSettings\Pages\Settings;
use SalemAljebaly\FilamentMapPicker\MapPicker;
use UnitEnum;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class MosqueSettings extends Settings
{
    use HasPageShield;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Mosque Settings');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('General'))
                            ->schema([
                                TextInput::make('general.name')
                                    ->label(__('Name'))
                                    ->required(),

                                Textarea::make('general.description')
                                    ->label(__('Description'))
                                    ->rows(3),

                                Textarea::make('general.address')
                                    ->label(__('Address'))
                                    ->rows(2),
                                Hidden::make('location.latitude'),
                                Hidden::make('location.longitude'),

                                MapPicker::make('location.map')
                                    ->label(__('Location'))
                                    ->latlngFields('location.latitude', 'location.longitude')
                                    ->searchable()
                                    ->collapsibleSearch()
                                    ->draggable()
                                    ->height(320)
                                    ->dehydrated(false),
                                PhoneInput::make('general.primary_phone')
                                    ->label(__('Primary Phone')),
                                PhoneInput::make('general.secondary_phone')
                                    ->label(__('Secondary Phone')),
                                TextInput::make('general.email')
                                    ->label(__('Email'))
                                    ->email(),

                            ]),

                        Tab::make(__('Branding'))
                            ->schema([
                                FileUpload::make('branding.logo')
                                    ->label(__('Logo'))
                                    ->image()
                                    ->visibility('public')
                                    ->directory('settings'),

                                FileUpload::make('branding.favicon')
                                    ->label(__('Favicon'))
                                    ->image()
                                    ->visibility('public')
                                    ->directory('settings'),
                            ]),

                        Tab::make(__('Social'))
                            ->schema([
                                TextInput::make('social.facebook')
                                    ->label(__('Facebook'))
                                    ->url(),

                                TextInput::make('social.twitter')
                                    ->label(__('Twitter / X'))
                                    ->url(),

                                TextInput::make('social.instagram')
                                    ->label(__('Instagram'))
                                    ->url(),

                                TextInput::make('social.youtube')
                                    ->label(__('YouTube'))
                                    ->url(),
                            ]),

                        Tab::make(__('Prayer'))
                            ->schema([
                                Select::make('prayer.method')
                                    ->label(__('Prayer Method'))
                                    ->options(PrayerMethod::class)
                                    ->required(),

                                Select::make('prayer.calculation_method')
                                    ->label(__('Calculation Method'))
                                    ->options(CalculationMethod::class)
                                    ->required(),

                                TextInput::make('prayer.timezone')
                                    ->label(__('Timezone'))
                                    ->placeholder('America/New_York'),

                                TextInput::make('prayer.adjustment_factor')
                                    ->label(__('Adjustment Factor (minutes)'))->numeric(),

                                TextInput::make('prayer.iqamah_offset')
                                    ->label(__('Time to Iqamah (minutes)'))
                                    ->hint(__('Added to adhan time to calculate iqamah when generating'))
                                    ->numeric(),

                                TextInput::make('prayer.jummah_offset')
                                    ->label(__('Jummah Iqamah Offset (minutes)'))
                                    ->hint(__('Minutes after Dhuhr adhan for Jummah iqamah'))
                                    ->numeric(),

                                TextInput::make('prayer.taraweeh_offset')
                                    ->label(__('Taraweeh After Isha (minutes)'))
                                    ->hint(__('Minutes after Isha for Taraweeh prayer'))
                                    ->numeric(),

                                TextInput::make('hijri.adjustment_factor')->numeric()
                                    ->label(__('Hijri Adjustment Factor (days)')),
                                AdvancedFileUpload::make('prayer.prayer_times_pdf')
                                    ->label(__('Prayer Times PDF'))
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->directory('settings')
                                    ->pdfDisplayPage(1) // Set default page
                                    ->pdfToolbar(true) // Enable toolbar
                                    ->pdfZoomLevel(100) // Set zoom level
                                    ->pdfFitType(PdfViewFit::FIT) // Set fit type
                                    ->pdfNavPanes(true), // Enable navigation panes

                            ]),

                        Tab::make(__('SEO'))
                            ->schema([
                                TextInput::make('seo.meta_title')
                                    ->label(__('Meta Title')),

                                Textarea::make('seo.meta_description')
                                    ->label(__('Meta Description'))
                                    ->rows(3),
                            ]),

                        Tab::make(__('Navigation'))
                            ->schema([
                                ...collect(PublicNavigation::sections())
                                    ->map(fn (string $label, string $section): Toggle => Toggle::make(PublicNavigation::settingKey($section))
                                        ->label($label)
                                        ->default(true))
                                    ->all(),
                            ])
                            ->columns(2),

                        Tab::make(__('Custom Code'))
                            ->schema([
                                CodeEditor::make('custom_code.header')
                                    ->label(__('Header Code'))
                                    ->hint(__('Injected inside <head> tag (e.g. GA4, Meta Pixel). Scripts only run after the visitor accepts analytics cookies.'))
                                    ->language(Language::Html)
                                    ->columnSpanFull(),

                                CodeEditor::make('custom_code.body')
                                    ->label(__('Body Code'))
                                    ->hint(__('Injected after <body> tag (e.g. tracking pixels, chat widgets). Scripts only run after the visitor accepts analytics cookies; <noscript> fallbacks are removed.'))
                                    ->language(Language::Html)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
