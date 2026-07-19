@extends('layouts.public')
@section('title', __('Prayer Times'))
@section('content')

    @if($today)
        <div class="bg-emerald-900 text-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">

                <div class="mb-6">
                    <p class="text-xs font-medium text-emerald-400 uppercase tracking-widest mb-1">
                        {{ __("Today's Prayer Times") }}
                    </p>
                    <h2 class="text-xl font-semibold text-white">
                        {{ \App\Support\LocalizedDate::date($today->date) }}
                    </h2>
                    <p class="text-emerald-300/80 text-sm mt-0.5">{{ \App\Support\LocalizedDate::hijri($today->date) }}</p>
                </div>

                @php
                    $prayers = [
                        ['key' => 'fajr',    'label' => __('Fajr'),    'adhan' => $today->fajr_adhan,    'iqamah' => $today->fajr_iqamah],
                        ['key' => 'sunrise', 'label' => __('Sunrise'), 'adhan' => $today->sunrise,       'iqamah' => null],
                        ['key' => 'dhuhr',   'label' => __('Dhuhr'),   'adhan' => $today->dhuhr_adhan,   'iqamah' => $today->dhuhr_iqamah],
                        ['key' => 'asr',     'label' => __('Asr'),     'adhan' => $today->asr_adhan,     'iqamah' => $today->asr_iqamah],
                        ['key' => 'maghrib', 'label' => __('Maghrib'), 'adhan' => $today->maghrib_adhan, 'iqamah' => $today->maghrib_iqamah],
                        ['key' => 'isha',    'label' => __('Isha'),    'adhan' => $today->isha_adhan,    'iqamah' => $today->isha_iqamah],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach($prayers as $prayer)
                        <div class="bg-white/8 border border-white/10 rounded-xl p-4 text-center">
                            <div class="text-xs font-medium text-emerald-400 uppercase tracking-wider mb-2">
                                {{ $prayer['label'] }}
                            </div>
                            <div class="text-lg font-semibold text-white tabular-nums">
                                {{ \App\Support\LocalizedDate::time($prayer['adhan']) ?? '—' }}
                            </div>
                            @if($prayer['iqamah'])
                                <div class="text-xs text-emerald-300 mt-1.5 tabular-nums">
                                    {{ \App\Support\LocalizedDate::time($prayer['iqamah']) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($today->jummah_adhan)
                    <div class="mt-5 bg-amber-500/10 border border-amber-400/25 rounded-xl px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-center gap-3 sm:border-e border-amber-400/20 sm:pe-5">
                            <div class="w-9 h-9 rounded-lg bg-amber-400/15 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-amber-400 uppercase tracking-widest">{{ __("Jumu'ah") }}</p>
                                <p class="text-xs text-amber-300/70 mt-0.5">{{ __('Friday Prayer') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-x-6 gap-y-3">

                            <div>
                                <p class="text-[10px] font-medium text-amber-400 uppercase tracking-wider">{{ __('Salah') }}</p>
                                <p class="text-base font-bold text-amber-100 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_adhan) }}</p>
                            </div>
                            @if($today->jummah_khutba_time)
                                <div>
                                    <p class="text-[10px] font-medium text-amber-400/70 uppercase tracking-wider">{{ __('Khutbah') }}</p>
                                    <p class="text-base font-bold text-amber-200 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_khutba_time) }}</p>
                                </div>
                            @endif
                            @if($today->jummah_iqamah)
                                <div>
                                    <p class="text-[10px] font-medium text-amber-400/70 uppercase tracking-wider">{{ __('Iqamah') }}</p>
                                    <p class="text-base font-bold text-amber-200 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_iqamah) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

        @php
            $prevMonth = $month == 1 ? 12 : $month - 1;
            $prevYear  = $month == 1 ? $year - 1 : $year;
            $nextMonth = $month == 12 ? 1 : $month + 1;
            $nextYear  = $month == 12 ? $year + 1 : $year;
            $prayerTimesPdf = setting('prayer.prayer_times_pdf');
            $tablePrayers = [
                ['key' => 'fajr', 'label' => __('Fajr')],
                ['key' => 'sunrise', 'label' => __('Sunrise')],
                ['key' => 'dhuhr', 'label' => __('Dhuhr')],
                ['key' => 'asr', 'label' => __('Asr')],
                ['key' => 'maghrib', 'label' => __('Maghrib')],
                ['key' => 'isha', 'label' => __('Isha')],
            ];

            $isCurrentMonth = $year == now()->year && $month == now()->month;
            $pastTimes = $isCurrentMonth ? $prayerTimes->filter(fn ($pt) => $pt->date->isBefore(today())) : collect();
            $upcomingTimes = $isCurrentMonth ? $prayerTimes->filter(fn ($pt) => ! $pt->date->isBefore(today())) : $prayerTimes;
        @endphp

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">{{ __('Monthly Schedule') }}</p>
                <h2 class="mt-1 text-xl font-semibold text-neutral-900 sm:text-2xl">
                    {{ \App\Support\LocalizedDate::monthYear(\Carbon\Carbon::createFromDate($year, $month, 1)) }}
                </h2>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:justify-end">
                @if($prayerTimesPdf)
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($prayerTimesPdf) }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="col-span-2 inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition-colors hover:border-emerald-300 hover:bg-emerald-100 sm:col-span-1">
                        <x-icon name="heroicon-o-document-arrow-down" class="h-4 w-4"/>
                        {{ __('Download PDF') }}
                    </a>
                @endif
                <a href="{{ route('prayer-times.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:border-neutral-300 hover:bg-neutral-50">
                    <x-icon name="heroicon-o-chevron-left" class="h-3.5 w-3.5 rtl:rotate-180"/>
                    {{ __('Previous') }}
                </a>
                <a href="{{ route('prayer-times.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:border-neutral-300 hover:bg-neutral-50">
                    {{ __('Next') }}
                    <x-icon name="heroicon-o-chevron-right" class="h-3.5 w-3.5 rtl:rotate-180"/>
                </a>
            </div>
        </div>

        <div class="space-y-4 md:hidden">
            @if($pastTimes->isNotEmpty())
                <details class="group overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50/60">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3.5 text-sm font-semibold text-neutral-600 transition-colors hover:bg-neutral-100/70">
                        <span class="inline-flex items-center gap-2">
                            <x-icon name="heroicon-o-clock" class="h-4 w-4 text-neutral-400"/>
                            {{ __('Past days this month') }}
                            <span class="rounded-full bg-neutral-200 px-2 py-0.5 text-[11px] font-semibold text-neutral-600">{{ $pastTimes->count() }}</span>
                        </span>
                        <x-icon name="heroicon-o-chevron-down" class="h-4 w-4 text-neutral-400 transition-transform group-open:rotate-180"/>
                    </summary>
                    <div class="space-y-4 border-t border-neutral-200 bg-white/50 p-4">
                        @foreach($pastTimes as $pt)
                            @include('pages.prayer-times.partials.card', ['pt' => $pt])
                        @endforeach
                    </div>
                </details>
            @endif

            @forelse($upcomingTimes as $pt)
                @include('pages.prayer-times.partials.card', ['pt' => $pt])
            @empty
                <p class="rounded-2xl border border-neutral-200 bg-white px-4 py-8 text-center text-sm text-neutral-500">{{ __('No prayer times available.') }}</p>
            @endforelse
        </div>

        @if($pastTimes->isNotEmpty())
            <details class="group mb-4 hidden overflow-hidden rounded-2xl border border-neutral-200 bg-neutral-50/60 md:block">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-5 py-3.5 text-sm font-semibold text-neutral-600 transition-colors hover:bg-neutral-100/70">
                    <span class="inline-flex items-center gap-2">
                        <x-icon name="heroicon-o-clock" class="h-4 w-4 text-neutral-400"/>
                        {{ __('Past days this month') }}
                        <span class="rounded-full bg-neutral-200 px-2 py-0.5 text-[11px] font-semibold text-neutral-600">{{ $pastTimes->count() }}</span>
                    </span>
                    <x-icon name="heroicon-o-chevron-down" class="h-4 w-4 text-neutral-400 transition-transform group-open:rotate-180"/>
                </summary>
                <div class="overflow-x-auto border-t border-neutral-200 bg-white">
                    <table class="min-w-full border-collapse text-sm">
                        <thead>
                        <tr class="border-b border-neutral-200 bg-neutral-50/90">
                            <th class="px-4 py-4 text-start text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ __('Date') }}</th>
                            @foreach($tablePrayers as $prayer)
                                <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ $prayer['label'] }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200/80">
                        @foreach($pastTimes as $pt)
                            @include('pages.prayer-times.partials.row', ['pt' => $pt])
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        @endif

        <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-sm">
                    <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50/90">
                        <th class="px-4 py-4 text-start text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ __('Date') }}</th>
                        @foreach($tablePrayers as $prayer)
                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ $prayer['label'] }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200/80">
                    @forelse($upcomingTimes as $pt)
                        @include('pages.prayer-times.partials.row', ['pt' => $pt])
                    @empty
                        <tr>
                            <td colspan="{{ count($tablePrayers) + 1 }}" class="px-4 py-8 text-center text-sm text-neutral-500">{{ __('No prayer times available.') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php
        $specialPrayers = \App\Models\SpecialPrayer::where('date', '>=', today())
            ->orderBy('date')
            ->orderBy('time')
            ->limit(10)
            ->get();
    @endphp

    @if($specialPrayers->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-10">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4">{{ __('Upcoming Special Prayers') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($specialPrayers as $sp)
                    @php
                        $isToday = $sp->date->isToday();
                        $location = $sp->location ?? [];
                        $latitude = data_get($location, 'latitude');
                        $longitude = data_get($location, 'longitude');
                        $address = data_get($location, 'address');
                        $googleMapsUrl = filled($latitude) && filled($longitude)
                            ? 'https://www.google.com/maps/search/?api=1&query='.$latitude.','.$longitude
                            : null;
                    @endphp
                    <div class="relative border {{ $isToday ? 'border-emerald-300 bg-emerald-50' : 'border-neutral-200 bg-white' }} rounded-xl p-4 transition-colors hover:shadow-sm">
                        @if($isToday)
                            <span class="absolute top-3 right-3 text-[10px] font-medium bg-emerald-100 text-emerald-700 border border-emerald-200 rounded px-1.5 py-0.5">{{ __('Today') }}</span>
                        @endif
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                                {{ match($sp->type->value) {
                                    'ramadan' => 'bg-emerald-100',
                                    'eid' => 'bg-amber-100',
                                    'weekly' => 'bg-blue-100',
                                    default => 'bg-neutral-100',
                                } }}">
                                <svg class="w-5 h-5 {{ match($sp->type->value) {
                                    'ramadan' => 'text-emerald-600',
                                    'eid' => 'text-amber-600',
                                    'weekly' => 'text-blue-600',
                                    default => 'text-neutral-600',
                                } }}" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-neutral-900 text-sm">{{ $sp->name }}</h3>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium mt-1
                                    {{ match($sp->type->value) {
                                        'ramadan' => 'bg-emerald-100 text-emerald-700',
                                        'eid' => 'bg-amber-100 text-amber-700',
                                        'weekly' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-neutral-100 text-neutral-600',
                                    } }}">
                                    {{ $sp->type->getLabel() }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">{{ __('Date') }}</span>
                                <span class="text-neutral-700 font-medium text-center">{{ \App\Support\LocalizedDate::date($sp->date) }} &middot; {{ \App\Support\LocalizedDate::weekday($sp->date) }} <br> <sub>{{ \App\Support\LocalizedDate::hijri($sp->date) }}</sub></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">{{ __('Time') }}</span>
                                <span class="text-neutral-900 font-semibold">{{ \App\Support\LocalizedDate::time($sp->time) }}</span>
                            </div>
                            @if($sp->end_time)
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">{{ __('Ends') }}</span>
                                    <span class="text-neutral-700">{{ \App\Support\LocalizedDate::time($sp->end_time) }}</span>
                                </div>
                            @endif
                            @if($address)
                                <div class="flex justify-between gap-3">
                                    <span class="text-neutral-500">{{ __('Location') }}</span>
                                    <span class="text-end text-neutral-700 font-medium">{{ $address }}</span>
                                </div>
                            @endif
                        </div>
                        @if($sp->description)
                            <p class="text-neutral-500 text-xs mt-2">{{ $sp->description }}</p>
                        @endif
                        @if($googleMapsUrl)
                            <a href="{{ $googleMapsUrl }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="mt-3 inline-flex items-center gap-2 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-100">
                                <x-icon name="heroicon-o-map-pin" class="h-4 w-4" />
                                {{ __('Open in Google Maps') }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection
