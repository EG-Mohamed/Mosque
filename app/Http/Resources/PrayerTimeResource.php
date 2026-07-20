<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class PrayerTimeResource extends ApiResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->dateString($this->date),
            'fajr' => [
                'adhan' => $this->fajr_adhan,
                'iqamah' => $this->fajr_iqamah,
            ],
            'sunrise' => $this->sunrise,
            'dhuhr' => [
                'adhan' => $this->dhuhr_adhan,
                'iqamah' => $this->dhuhr_iqamah,
            ],
            'asr' => [
                'adhan' => $this->asr_adhan,
                'iqamah' => $this->asr_iqamah,
            ],
            'maghrib' => [
                'adhan' => $this->maghrib_adhan,
                'iqamah' => $this->maghrib_iqamah,
            ],
            'isha' => [
                'adhan' => $this->isha_adhan,
                'iqamah' => $this->isha_iqamah,
            ],
            'jummah' => [
                'adhan' => $this->jummah_adhan,
                'khutba_time' => $this->jummah_khutba_time,
                'iqamah' => $this->jummah_iqamah,
            ],
            'created_at' => $this->dateTimeString($this->created_at),
            'updated_at' => $this->dateTimeString($this->updated_at),
        ];
    }
}
