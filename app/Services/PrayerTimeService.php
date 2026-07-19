<?php

namespace App\Services;

use App\Models\PrayerTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PrayerTimeService
{
    private const COLUMNS = [
        'fajr_adhan',
        'fajr_iqamah',
        'sunrise',
        'dhuhr_adhan',
        'dhuhr_iqamah',
        'asr_adhan',
        'asr_iqamah',
        'maghrib_adhan',
        'maghrib_iqamah',
        'isha_adhan',
        'isha_iqamah',
        'jummah_adhan',
        'jummah_khutba_time',
        'jummah_iqamah',
    ];

    public function getTodayPrayer(): ?PrayerTime
    {
        return Cache::remember(
            'prayer_today',
            now()->endOfDay()->diffInSeconds(now()),
            fn () => PrayerTime::query()
                ->whereDate('date', today())
                ->first()
        );
    }

    public function getMonthPrayers(int $year, int $month): Collection
    {
        return PrayerTime::forMonth($year, $month)->get();
    }

    public function generateMonth(int $year, int $month, bool $overwrite = false): int
    {
        $rows = $this->buildMonthRows($year, $month);

        return $this->persistRows($rows, $overwrite);
    }

    public function generateYear(int $year, bool $overwrite = false): int
    {
        $rows = [];

        for ($month = 1; $month <= 12; $month++) {
            $rows = array_merge($rows, $this->buildMonthRows($year, $month));
        }

        return $this->persistRows($rows, $overwrite);
    }

    private function buildMonthRows(int $year, int $month): array
    {
        $days = $this->fetchMonthFromApi($year, $month);
        $rows = [];

        foreach ($days as $day) {
            $payload = $this->buildDayPayload($day);

            if ($payload) {
                $rows[] = $payload;
            }
        }

        return $rows;
    }

    private function persistRows(array $rows, bool $overwrite): int
    {
        if ($rows === []) {
            return 0;
        }

        $dates = array_column($rows, 'date');

        $existing = PrayerTime::query()
            ->getQuery()
            ->withoutCache()
            ->whereIn('date', $dates)
            ->get()
            ->keyBy(fn ($record): string => Carbon::parse($record->date)->toDateString());

        $written = 0;

        DB::transaction(function () use ($rows, $existing, $overwrite, &$written): void {
            $now = now();

            foreach ($rows as $row) {
                $current = $existing->get($row['date']);

                if (! $current) {
                    PrayerTime::query()->insert(array_merge($row, [
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]));
                    $written++;

                    continue;
                }

                $updates = $this->resolveUpdates($row, (array) $current, $overwrite);

                if ($updates !== []) {
                    PrayerTime::query()
                        ->whereKey($current->id)
                        ->update($updates);
                    $written++;
                }
            }
        });

        Cache::forget('prayer_today');
        PrayerTime::query()->getQuery()->flushCache();

        return $written;
    }

    private function resolveUpdates(array $row, array $current, bool $overwrite): array
    {
        $updates = [];

        foreach (self::COLUMNS as $column) {
            if (! array_key_exists($column, $row)) {
                continue;
            }

            if ($overwrite || blank($current[$column] ?? null)) {
                $updates[$column] = $row[$column];
            }
        }

        return $updates;
    }

    private function fetchMonthFromApi(int $year, int $month): array
    {
        $response = Http::get("https://api.aladhan.com/v1/calendar/{$year}/{$month}", [
            'latitude' => setting('location.latitude') ?? 40.7128,
            'longitude' => setting('location.longitude') ?? -74.0060,
            'method' => setting('prayer.calculation_method') ?? 2,
        ]);

        if (! $response->successful()) {
            return [];
        }

        return $response->json('data') ?? [];
    }

    private function buildDayPayload(array $day): ?array
    {
        $gregorian = $day['date']['gregorian']['date'] ?? null;
        $timings = $day['timings'] ?? null;

        if (! $gregorian || ! $timings) {
            return null;
        }

        $date = Carbon::createFromFormat('d-m-Y', $gregorian);
        $adjustment = (int) setting('prayer.adjustment_factor');
        $iqamahOffset = (int) setting('prayer.iqamah_offset');

        $fajrAdhan = $this->adjustTime($timings['Fajr'] ?? null, $adjustment);
        $dhuhrAdhan = $this->adjustTime($timings['Dhuhr'] ?? null, $adjustment);
        $asrAdhan = $this->adjustTime($timings['Asr'] ?? null, $adjustment);
        $maghribAdhan = $this->adjustTime($timings['Maghrib'] ?? null, $adjustment);
        $ishaAdhan = $this->adjustTime($timings['Isha'] ?? null, $adjustment);

        $data = [
            'date' => $date->toDateString(),
            'fajr_adhan' => $fajrAdhan,
            'fajr_iqamah' => $this->adjustTime($fajrAdhan, $iqamahOffset),
            'sunrise' => $this->adjustTime($timings['Sunrise'] ?? null, $adjustment),
            'dhuhr_adhan' => $dhuhrAdhan,
            'dhuhr_iqamah' => $this->adjustTime($dhuhrAdhan, $iqamahOffset),
            'asr_adhan' => $asrAdhan,
            'asr_iqamah' => $this->adjustTime($asrAdhan, $iqamahOffset),
            'maghrib_adhan' => $maghribAdhan,
            'maghrib_iqamah' => $this->adjustTime($maghribAdhan, $iqamahOffset),
            'isha_adhan' => $ishaAdhan,
            'isha_iqamah' => $this->adjustTime($ishaAdhan, $iqamahOffset),
        ];

        if ($date->isFriday() && $dhuhrAdhan) {
            $jummahOffset = (int) setting('prayer.jummah_offset');
            $data['jummah_adhan'] = $dhuhrAdhan;
            $data['jummah_khutba_time'] = $this->adjustTime($dhuhrAdhan, -15);
            $data['jummah_iqamah'] = $this->adjustTime($dhuhrAdhan, $jummahOffset ?: $iqamahOffset);
        }

        return $data;
    }

    private function adjustTime(?string $time, int $minutes): ?string
    {
        if (! $time) {
            return null;
        }

        $time = trim(preg_replace('/\(.*\)/', '', $time));

        if ($minutes === 0) {
            return Carbon::parse($time)->format('H:i');
        }

        return Carbon::parse($time)->addMinutes($minutes)->format('H:i');
    }
}
