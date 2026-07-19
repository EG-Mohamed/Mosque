<?php

namespace App\Console\Commands;

use App\Services\PrayerTimeService;
use Illuminate\Console\Command;

class GeneratePrayerTimes extends Command
{
    protected $signature = 'mosque:generate-prayer-times
                            {--year= : Year to generate (defaults to current year)}
                            {--month= : Month to generate (defaults to current month)}
                            {--year-only : Generate the whole year instead of a single month}
                            {--overwrite : Overwrite existing prayer times instead of only filling empty fields}';

    protected $description = 'Generate prayer times for a given month or year using the API';

    public function handle(PrayerTimeService $service): int
    {
        $year = (int) ($this->option('year') ?? now()->year);
        $overwrite = (bool) $this->option('overwrite');

        if ($this->option('year-only')) {
            $this->info("Generating prayer times for {$year}...");
            $generated = $service->generateYear($year, $overwrite);
        } else {
            $month = (int) ($this->option('month') ?? now()->month);
            $this->info("Generating prayer times for {$year}-{$month}...");
            $generated = $service->generateMonth($year, $month, $overwrite);
        }

        $this->info("Generated {$generated} prayer time entries.");

        return self::SUCCESS;
    }
}
