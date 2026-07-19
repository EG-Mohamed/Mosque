<?php

namespace App\Filament\Imports;

use App\Models\PrayerTime;
use App\Support\PrayerTimeFormatter;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class PrayerTimeImporter extends Importer
{
    protected static ?string $model = PrayerTime::class;

    private const ADHAN_PRAYERS = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];

    public function getJobConnection(): ?string
    {
        return 'sync';
    }

    public static function getColumns(): array
    {
        $columns = [
            ImportColumn::make('date')
                ->label(__('Date'))
                ->exampleHeader(__('Date'))
                ->requiredMapping()
                ->rules(['required'])
                ->castStateUsing(fn (?string $state): ?string => PrayerTimeFormatter::normalizeDate($state)),

            ImportColumn::make('sunrise')
                ->label(__('Sunrise'))
                ->exampleHeader(__('Sunrise'))
                ->castStateUsing(fn (?string $state): ?string => PrayerTimeFormatter::normalizeTime($state)),
        ];

        foreach (self::ADHAN_PRAYERS as $prayer) {
            $columns[] = ImportColumn::make("{$prayer}_adhan")
                ->label(__(ucfirst($prayer)))
                ->exampleHeader(__(ucfirst($prayer)))
                ->castStateUsing(fn (?string $state): ?string => PrayerTimeFormatter::normalizeTime($state));
        }

        $columns[] = ImportColumn::make('jummah_adhan')
            ->label(__("Jumu'ah"))
            ->exampleHeader(__("Jumu'ah"))
            ->castStateUsing(fn (?string $state): ?string => PrayerTimeFormatter::normalizeTime($state));

        return $columns;
    }

    public static function getOptionsFormComponents(): array
    {
        $defaultOffset = (int) setting('prayer.iqamah_offset');
        $defaultJummahOffset = (int) setting('prayer.jummah_offset') ?: $defaultOffset;

        $offsetFields = [];

        foreach (self::ADHAN_PRAYERS as $prayer) {
            $offsetFields[] = TextInput::make("{$prayer}_iqamah_offset")
                ->label(__(ucfirst($prayer)))
                ->numeric()
                ->default($defaultOffset);
        }

        $offsetFields[] = TextInput::make('jummah_iqamah_offset')
            ->label(__("Jumu'ah"))
            ->numeric()
            ->default($defaultJummahOffset);

        return [
            Toggle::make('overwrite')
                ->label(__('Overwrite existing times'))
                ->helperText(__('When off, existing days are kept and only empty fields are filled.'))
                ->default(false),

            Section::make(__('Iqamah offset (min)'))
                ->description(__('Applied as adhan + offset when the file has no iqamah for that prayer.'))
                ->schema([
                    Grid::make(6)->schema($offsetFields),
                ]),
        ];
    }

    public function resolveRecord(): ?PrayerTime
    {
        $date = PrayerTimeFormatter::normalizeDate($this->data['date'] ?? null);

        if (! $date) {
            return null;
        }

        $existing = PrayerTime::query()
            ->getQuery()
            ->withoutCache()
            ->where('date', $date)
            ->exists();

        if ($existing && ! ($this->options['overwrite'] ?? false)) {
            return null;
        }

        return PrayerTime::firstOrNew(['date' => $date]);
    }

    protected function beforeSave(): void
    {
        $prayers = [...self::ADHAN_PRAYERS, 'jummah'];

        foreach ($prayers as $prayer) {
            $adhan = $this->record->{"{$prayer}_adhan"};
            $iqamah = $this->record->{"{$prayer}_iqamah"};
            $offset = (int) ($this->options["{$prayer}_iqamah_offset"] ?? 0);

            if ($adhan && blank($iqamah) && $offset !== 0) {
                $this->record->{"{$prayer}_iqamah"} = PrayerTimeFormatter::addMinutes($adhan, $offset);
            }
        }
    }

    protected function afterSave(): void
    {
        Cache::forget('prayer_today');
        PrayerTime::query()->getQuery()->flushCache();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count prayer times imported.', ['count' => Number::format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__(':count rows failed to import.', ['count' => Number::format($failedRowsCount)]);
        }

        return $body;
    }
}
