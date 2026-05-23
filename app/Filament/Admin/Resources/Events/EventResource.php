<?php

namespace App\Filament\Admin\Resources\Events;

use App\Filament\Admin\Resources\Events\Pages\CreateEvent;
use App\Filament\Admin\Resources\Events\Pages\EditEvent;
use App\Filament\Admin\Resources\Events\Pages\ListEvents;
use App\Filament\Admin\Resources\Events\Schemas\EventForm;
use App\Filament\Admin\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    public static function getModelLabel(): string
    {
        return __('Event');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Events');
    }
    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('Content');
    }
    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::OutlinedCalendarDays;
    }



    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
