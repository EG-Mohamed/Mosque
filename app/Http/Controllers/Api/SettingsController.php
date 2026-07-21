<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function settings(): array
    {
        $settings = app('setting')->get();

        $logo = data_get($settings, 'branding.logo');

        return [
            'name' => data_get($settings, 'general.name'),
            'description' => data_get($settings, 'general.description'),
            'url' => config('app.url'),
            'logo' => $logo ? Storage::url($logo) : null,
            'email' => data_get($settings, 'general.email'),
            'address' => data_get($settings, 'general.address'),
            'primary_phone' => data_get($settings, 'general.primary_phone'),
            'secondary_phone' => data_get($settings, 'general.secondary_phone'),
            'social' => data_get($settings, 'social', []),
            'location' => data_get($settings, 'location', []),
        ];
    }
}