<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', setting('general.name') ?? 'Mosque')</title>
    <meta name="description" content="@yield('description', setting('seo.meta_description') ?? '')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Amiri+Quran&display=swap" rel="stylesheet">
    @if(setting('branding.favicon'))
        <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Facades\Storage::url(setting('branding.favicon')) }}">
    @endif
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
    {!! \App\Support\ConsentGate::scripts(setting('custom_code.header')) !!}
</head>
<body class="bg-white text-neutral-800 font-sans antialiased">
    @include('components.cookie-consent')
    {!! \App\Support\ConsentGate::scripts(setting('custom_code.body')) !!}
    @include('components.navigation')
    <main>
        @yield('content')
    </main>
    @include('components.footer')
    @yield('scripts')
    @livewire('notifications') {{-- Only required if you wish to send flash notifications --}}

    @filamentScripts
</body>
</html>
