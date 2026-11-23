@php
$data = $block['data'] ?? $data ?? [];
$hasMap = !empty(setting('location.latitude')) && !empty(setting('location.longitude'));
@endphp

@if(\App\Support\PublicNavigation::isEnabled('contact'))
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Find Us') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Contact & Location') }}</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="space-y-6">
                <div class="bg-emerald-50 rounded-2xl p-8 border border-emerald-100">
                    <h3 class="text-xl font-bold text-neutral-900 mb-6">
                        {{ setting('general.name') ?? __('Mosque') }}
                    </h3>

                    <div class="space-y-5">
                        @if(setting('general.address'))
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide mb-0.5">{{ __('Address') }}</p>
                                    <p class="text-neutral-700 font-medium leading-relaxed">
                                        {{ setting('general.address') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        @if(setting('general.phone'))
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide mb-0.5">{{ __('Phone') }}</p>
                                    <a href="tel:{{ setting('general.phone') }}" class="text-neutral-700 font-medium hover:text-emerald-600 transition-colors">
                                        {{ setting('general.phone') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(setting('general.email'))
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide mb-0.5">{{ __('Email') }}</p>
                                    <a href="mailto:{{ setting('general.email') }}" class="text-neutral-700 font-medium hover:text-emerald-600 transition-colors break-all">
                                        {{ setting('general.email') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(setting('general.description'))
                    <div class="bg-white rounded-xl border border-neutral-100 p-6 shadow-sm">
                        <p class="text-neutral-600 leading-relaxed">
                            {{ setting('general.description') }}
                        </p>
                    </div>
                @endif
            </div>

            <div data-consent-embed class="relative rounded-2xl overflow-hidden shadow-lg border border-neutral-200 bg-neutral-100 min-h-[400px] flex items-center justify-center">
                @if($hasMap)
                    <x-consent-placeholder provider="Google Maps" />
                    <iframe
                        data-consent-category="media"
                        data-consent-src="https://maps.google.com/maps?q={{ setting('location.latitude') }},{{ setting('location.longitude') }}&z=15&output=embed"
                        class="w-full h-full min-h-[400px]"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="{{ __('Mosque Location') }}">
                    </iframe>
                @else
                    <div class="text-center p-10 text-neutral-400">
                        <svg class="w-16 h-16 mx-auto mb-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        <p>{{ __('Map location not configured.') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</section>
@endif
