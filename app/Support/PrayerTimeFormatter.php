<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class PrayerTimeFormatter
{
    public static function normalizeTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }

        $time = trim(preg_replace('/\(.*\)/', '', $time));

        if ($time === '') {
            return null;
        }

        return Carbon::parse($time)->format('H:i:s');
    }

    public static function addMinutes(?string $time, int $minutes): ?string
    {
        $normalized = self::normalizeTime($time);

        if (! $normalized) {
            return null;
        }

        if ($minutes === 0) {
            return $normalized;
        }

        return Carbon::parse($normalized)->addMinutes($minutes)->format('H:i:s');
    }

    public static function normalizeDate(?string $date): ?string
    {
        if (! $date) {
            return null;
        }

        $date = trim($date);

        if ($date === '') {
            return null;
        }

        foreach (['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y', 'd.m.Y'] as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $date);
            } catch (\Throwable) {
                continue;
            }

            if ($parsed->format($format) === $date) {
                return $parsed->toDateString();
            }
        }

        return Carbon::parse($date)->toDateString();
    }
}
