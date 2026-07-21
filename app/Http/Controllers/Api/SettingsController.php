<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function settings()
    {
        $settings = app('setting')->get();
        return [
            'name' => $settings['general']['name'] ?? config('app.name'),
            'description' => $settings['general']['description'] ?? config('app.description'),
            'url' => $settings['general']['url'] ?? config('app.url'),
            'logo' => $settings['branding']['logo'] ?? config('app.logo'),
            'favicon' => $settings['branding']['favicon'] ?? config('app.favicon'),
            'email' => $settings['general']['email'] ?? config('app.email'),
            'address' => $settings['general']['address'] ?? config('app.address'),
            'primary_phone' => $settings['general']['primary_phone'] ?? config('app.primary_phone'),
            'secondary_phone' => $settings['general']['secondary_phone'] ?? config('app.secondary_phone'),
            'social' => $settings['social'],
            'location' => $settings['location'],
        ];
    }
}
