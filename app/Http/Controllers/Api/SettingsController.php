<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function settings()
    {
        $settings = app('setting')->get();
        return [
            'name' => $settings['general']['name'],
            'description' => $settings['general']['description'],
            'url' => $settings['general']['url'],
            'logo' => Storage::url($settings['branding']['logo']),
            'email' => $settings['general']['email'],
            'address' => $settings['general']['address'],
            'primary_phone' => $settings['general']['primary_phone'],
            'secondary_phone' => $settings['general']['secondary_phone'],
            'social' => $settings['social'],
            'location' => $settings['location'],
        ];
    }
}
