<footer class="bg-neutral-900 text-neutral-400 border-t border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">

            {{-- Brand --}}
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    @if(setting('branding.logo'))
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(setting('branding.logo')) }}"
                             alt="{{ setting('general.name') }}" class="bg-white p-5 rounded">
                    @endif
                </div>

                @if(setting('general.description'))
                    <p class="text-xs leading-relaxed text-neutral-500 max-w-xs">
                        {{ setting('general.description') }}
                    </p>
                @endif

                <div class="space-y-1.5">
                    @if(setting('general.address'))
                        <div class="flex items-start gap-2 text-xs text-neutral-500">
                            <x-heroicon-o-map-pin class="w-3.5 h-3.5 mt-0.5 shrink-0 text-emerald-700" />
                            <span><a class="text-emerald-700 hover:text-emerald-900 transition-colors" href="https://www.google.com/maps/search/?api=1&query={{ setting('location.latitude') }},{{ setting('location.longitude') }}">{{ setting('general.address') }}</a></span>
                        </div>
                    @endif
                    @if(setting('general.phones'))
                        @foreach(setting('general.phones') as $phone)
                                <div class="flex items-center gap-2 text-xs text-neutral-500">
                                    <x-heroicon-o-phone class="w-3.5 h-3.5 shrink-0 text-emerald-700" />
                                    <a href="tel:{{ data_get($phone,'phone','-') }}" class="text-emerald-700 hover:text-emerald-900 transition-colors">
                                        <bdi>{{ data_get($phone,'phone','-') }}</bdi>
                                    </a>
                                </div>
                        @endforeach
                    @endif
                    @if(setting('general.email'))
                        <div class="flex items-center gap-2 text-xs text-neutral-500">
                            <x-heroicon-o-envelope class="w-3.5 h-3.5 shrink-0 text-emerald-700" />
                            <span><a href="mailto:{{ setting('general.email') }}">{{ setting('general.email') }}</a></span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-0.5">
                    @if(setting('social.facebook'))
                        <a href="{{ setting('social.facebook') }}" target="_blank" rel="noopener noreferrer"
                           class="text-neutral-600 hover:text-emerald-500 transition-colors">
                            <x-icon name="fab-facebook" class="w-4 h-4" />
                        </a>
                    @endif
                    @if(setting('social.twitter'))
                        <a href="{{ setting('social.twitter') }}" target="_blank" rel="noopener noreferrer"
                           class="text-neutral-600 hover:text-emerald-500 transition-colors">
                            <x-icon name="fab-x-twitter" class="w-4 h-4" />
                        </a>
                    @endif
                    @if(setting('social.instagram'))
                        <a href="{{ setting('social.instagram') }}" target="_blank" rel="noopener noreferrer"
                           class="text-neutral-600 hover:text-emerald-500 transition-colors">
                            <x-icon name="fab-instagram" class="w-4 h-4" />
                        </a>
                    @endif
                    @if(setting('social.youtube'))
                        <a href="{{ setting('social.youtube') }}" target="_blank" rel="noopener noreferrer"
                           class="text-neutral-600 hover:text-emerald-500 transition-colors">
                            <x-icon name="fab-youtube" class="w-4 h-4" />
                        </a>
                    @endif
                </div>
            </div>

            {{-- Today's Prayer Times --}}
            <div class="space-y-3">
                <h3 class="text-xs font-semibold text-neutral-300 uppercase tracking-wider">{{ __("Today's Prayer Times") }}</h3>
                @include('components.prayer-times-mini', [
                    'today' => \App\Models\PrayerTime::forDate(now()->toDateString())->first()
                ])
            </div>

        </div>
    </div>

    <div class="border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex flex-col sm:flex-row items-center justify-between gap-1.5">
            <p class="text-xs text-neutral-600">
                &copy; {{ date('Y') }} {{ setting('general.name') ?? __('Mosque') }}. {{ __('All rights reserved.') }}
            </p>
            <nav class="flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-xs" aria-label="{{ __('Legal') }}">
                <a href="{{ route('legal.privacy') }}" class="text-neutral-500 hover:text-emerald-400 transition-colors">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('legal.cookies') }}" class="text-neutral-500 hover:text-emerald-400 transition-colors">{{ __('Cookie Policy') }}</a>
                <a href="{{ route('legal.terms') }}" class="text-neutral-500 hover:text-emerald-400 transition-colors">{{ __('Terms of Use') }}</a>
                <button type="button" data-cc="show-preferencesModal" class="text-neutral-500 hover:text-emerald-400 transition-colors">{{ __('Cookie settings') }}</button>
            </nav>
            <p class="text-xs text-neutral-700">
                {{ __('Built with') }} <span class="text-emerald-800">&hearts;</span> {{ __('by') }}
                <a href="https://msaied.com" class="text-neutral-500 hover:text-emerald-400 transition-colors">Mohamed Said</a>
            </p>
        </div>
    </div>
</footer>