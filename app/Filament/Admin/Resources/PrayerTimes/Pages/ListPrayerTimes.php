<?php

namespace App\Filament\Admin\Resources\PrayerTimes\Pages;

use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use App\Filament\Imports\PrayerTimeImporter;
use App\Models\PrayerTime;
use App\Services\PrayerTimeService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListPrayerTimes extends ListRecords
{
    protected static string $resource = PrayerTimeResource::class;

    public function getDefaultActiveTab(): string
    {
        return 'upcoming';
    }

    public function getTabs(): array
    {
        return [
            'upcoming' => Tab::make(__('Upcoming'))
                ->icon(Heroicon::OutlinedCalendarDays)
                ->badge(PrayerTime::query()->whereDate('date', '>=', today())->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->whereDate('date', '>=', today())
                    ->reorder('date', 'asc')),

            'today' => Tab::make(__('Today'))
                ->icon(Heroicon::OutlinedSun)
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query): Builder => $query->whereDate('date', today())),

            'week' => Tab::make(__('This Week'))
                ->icon(Heroicon::OutlinedCalendar)
                ->badge(PrayerTime::query()->whereBetween('date', [today(), today()->addDays(6)])->count())
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->whereBetween('date', [today(), today()->addDays(6)])
                    ->reorder('date', 'asc')),

            'past' => Tab::make(__('Past'))
                ->icon(Heroicon::OutlinedArrowUturnLeft)
                ->modifyQueryUsing(fn (Builder $query): Builder => $query
                    ->whereDate('date', '<', today())
                    ->reorder('date', 'desc')),

            'all' => Tab::make(__('All'))
                ->icon(Heroicon::OutlinedBars3),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateMonthly')
                ->label(__('Generate Prayer Times'))
                ->outlined()
                ->color('success')
                ->schema([
                    Select::make('scope')
                        ->label(__('Scope'))
                        ->options([
                            'month' => __('Monthly'),
                            'year' => __('Yearly'),
                        ])
                        ->default('month')
                        ->live()
                        ->required(),

                    Select::make('month')
                        ->label(__('Month'))
                        ->options(array_combine(range(1, 12), array_map(
                            fn ($m) => date('F', mktime(0, 0, 0, $m, 1)),
                            range(1, 12)
                        )))
                        ->default(now()->month)
                        ->visible(fn (Get $get): bool => $get('scope') === 'month')
                        ->required(fn (Get $get): bool => $get('scope') === 'month'),

                    Select::make('year')
                        ->label(__('Year'))
                        ->options(array_combine(
                            range(now()->year - 1, now()->year + 2),
                            range(now()->year - 1, now()->year + 2)
                        ))
                        ->default(now()->year)
                        ->required(),

                    Toggle::make('overwrite')
                        ->label(__('Overwrite existing times'))
                        ->helperText(__('When off, existing days are kept and only empty fields are filled.'))
                        ->default(false),
                ])
                ->action(function (array $data, PrayerTimeService $service): void {
                    $overwrite = (bool) ($data['overwrite'] ?? false);

                    $generated = ($data['scope'] ?? 'month') === 'year'
                        ? $service->generateYear((int) $data['year'], $overwrite)
                        : $service->generateMonth((int) $data['year'], (int) $data['month'], $overwrite);

                    Notification::make()
                        ->title(__(':count prayer times generated', ['count' => $generated]))
                        ->success()
                        ->send();
                }),

            Action::make('fillJummahTimes')
                ->label(__('Fill Jummah Adhans'))
                ->icon('heroicon-o-calendar-days')
                ->outlined()
                ->color('warning')
                ->schema([
                    Grid::make(3)->schema([
                        TimePicker::make('jummah_adhan')
                            ->label(__('Jummah Adhan'))
                            ->required(),
                        TimePicker::make('jummah_khutba_time')
                            ->label(__('Jummah Khutba Time')),
                        TimePicker::make('jummah_iqamah')
                            ->label(__('Jummah Iqamah'))
                            ->required(),
                    ]),
                ])
                ->action(function (array $data): void {
                    $updated = PrayerTime::query()
                        ->whereRaw('DAYOFWEEK(date) = 6')
                        ->update($data);

                    Notification::make()
                        ->title(__(':count Friday records updated', ['count' => $updated]))
                        ->success()
                        ->send();
                }),

            ImportAction::make()
                ->label(__('Import Prayer Times'))
                ->icon('heroicon-o-arrow-up-tray')
                ->importer(PrayerTimeImporter::class),

            CreateAction::make(),
        ];
    }
}
