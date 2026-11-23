@extends('layouts.public')
@section('title', __('Contact Us'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Contact Us') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __("We'd love to hear from you") }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid gap-12 lg:grid-cols-2">

            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-neutral-900 mb-6">{{ __('Get In Touch') }}</h2>
                    <p class="text-neutral-600 leading-relaxed">
                        {{ __('Feel free to reach out to us for any inquiries, questions, or to get involved with our community.') }}
                    </p>
                </div>

                <div class="space-y-5">

                    @if(setting('general.address'))
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 shrink-0">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-800 mb-0.5">{{ __('Address') }}</p>
                                <a class="text-emerald-700 hover:text-emerald-900 transition-colors" href="https://www.google.com/maps/search/?api=1&query={{ setting('location.latitude') }},{{ setting('location.longitude') }}">{{ setting('general.address') }}</a>
                            </div>
                        </div>
                    @endif

                        @if(setting('general.primary_phone'))
                            <div class="flex items-start gap-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 shrink-0">
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-800 mb-0.5">{{ __('Primary Phone') }}</p>
                                    <a href="tel:{{ setting('general.primary_phone')}}" class="text-emerald-700 hover:text-emerald-900 transition-colors">
                                        <bdi>{{ setting('general.primary_phone')}}</bdi>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(setting('general.secondary_phone'))
                            <div class="flex items-start gap-4">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 shrink-0">
                                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-neutral-800 mb-0.5">{{ __('Secondary Phone') }}</p>
                                    <a href="tel:{{ setting('general.secondary_phone')}}" class="text-emerald-700 hover:text-emerald-900 transition-colors">
                                        <bdi>{{ setting('general.secondary_phone')}}</bdi>
                                    </a>
                                </div>
                            </div>
                        @endif

                    @if(setting('general.email'))
                        <div class="flex items-start gap-4">
                            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 shrink-0">
                                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral-800 mb-0.5">{{ __('Email') }}</p>
                                <a href="mailto:{{ setting('general.email') }}" class="text-emerald-700 hover:text-emerald-900 transition-colors">
                                    {{ setting('general.email') }}
                                </a>
                            </div>
                        </div>
                    @endif

                </div>

                @php
                    $socials = [
                        ['key' => 'social.facebook',  'label' => 'Facebook',  'icon' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                        ['key' => 'social.twitter',   'label' => 'Twitter',   'icon' => 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
                        ['key' => 'social.instagram', 'label' => 'Instagram', 'icon' => 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],
                        ['key' => 'social.youtube',   'label' => 'YouTube',   'icon' => 'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z M9.75 15.02l5.75-3.02-5.75-3.02v6.04z'],
                    ];
                    $activeSocials = array_filter($socials, fn($s) => !empty(setting($s['key'])));
                @endphp

                @if(!empty($activeSocials))
                    <div>
                        <p class="font-semibold text-neutral-800 mb-4">{{ __('Follow Us') }}</p>
                        <div class="flex gap-3">
                            @foreach($activeSocials as $social)
                                <a href="{{ setting($social['key']) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="{{ $social['label'] }}"
                                   class="flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 hover:bg-emerald-700 hover:text-white transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <path d="{{ $social['icon'] }}" />
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <div>
                @if(setting('location.latitude') && setting('location.longitude'))
                    <div data-consent-embed class="relative rounded-2xl overflow-hidden shadow-sm border border-neutral-200 h-96 lg:h-full min-h-80">
                        <x-consent-placeholder provider="Google Maps" />
                        <iframe
                            data-consent-category="media"
                            data-consent-src="https://www.google.com/maps?q={{ setting('location.latitude') }},{{ setting('location.longitude') }}&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0; min-height: 320px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="{{ __('Mosque location') }}">
                        </iframe>
                    </div>
                @else
                    <div class="rounded-2xl bg-emerald-50 border-2 border-dashed border-emerald-200 h-80 flex flex-col items-center justify-center text-center p-8">
                        <svg class="w-16 h-16 text-emerald-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <p class="text-emerald-400 font-medium">{{ __('Map location not configured') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-16">
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-8 lg:p-12">
            <h2 class="text-2xl font-bold text-neutral-900 mb-2">{{ __('Send Us a Message') }}</h2>
            <p class="text-neutral-500 mb-8">{{ __('Fill out the form below and we will get back to you as soon as possible.') }}</p>
            <livewire:contact-form />
        </div>
    </div>

@endsection
