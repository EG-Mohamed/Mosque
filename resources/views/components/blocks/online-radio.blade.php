@php
    $data = $block['data'] ?? $data ?? [];
    $radioSeed = $block['id'] ?? $data['id'] ?? md5(json_encode($data));
    $radioId = 'online-radio-'.preg_replace('/[^a-zA-Z0-9_-]/', '-', (string) $radioSeed);
    $language = ($data['language_mode'] ?? 'auto') === 'auto'
        ? app()->getLocale()
        : ($data['language_mode'] ?? 'ar');
    $config = [
        'blockId' => $radioId,
        'language' => $language,
        'locale' => app()->getLocale(),
        'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
        'routes' => [
            'radios' => route('quran-player.radios'),
        ],
        'labels' => [
            'loading' => __('Loading radio stations...'),
            'failed' => __('Unable to load radio stations right now.'),
            'retry' => __('Try again'),
            'empty' => __('No radio stations are available right now.'),
            'noResults' => __('No stations match your search.'),
            'ready' => __('Choose a station to start listening.'),
            'search' => __('Search radio stations'),
            'stations' => __('Radio stations'),
            'showing' => __(':count stations'),
            'nowPlaying' => __('Now playing'),
            'previous' => __('Previous station'),
            'next' => __('Next station'),
            'play' => __('Play :station'),
            'listening' => __('Listening live: :station'),
            'streamFailed' => __('This station stream is currently unavailable.'),
        ],
    ];
@endphp

<section class="relative overflow-hidden bg-[#041416] py-10 sm:py-14">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(44,194,149,0.16),transparent_36%),radial-gradient(circle_at_bottom_right,rgba(240,179,58,0.10),transparent_30%)]"></div>

    <div
        id="{{ $radioId }}"
        class="relative mx-auto max-w-7xl px-4 sm:px-6"
        data-online-radio='@json($config)'
        dir="{{ $config['dir'] }}"
    >
        <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-[#071d20]/90 p-4 text-white shadow-[0_24px_80px_rgba(0,0,0,0.28)] backdrop-blur sm:p-6 lg:p-8">
            @if(!empty($data['title']) || !empty($data['intro']))
                <div class="border-b border-white/10 pb-6">
                    @if(!empty($data['title']))
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-70"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                            </span>
                            {{ __('Live') }}
                        </div>
                        <h2 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl lg:text-4xl">{{ $data['title'] }}</h2>
                    @endif

                    @if(!empty($data['intro']))
                        <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">{{ $data['intro'] }}</p>
                    @endif
                </div>
            @endif

            <div class="mt-6 grid gap-5 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]">
                <div class="rounded-[1.5rem] border border-white/10 bg-[#021012] p-4 sm:p-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-300/10 text-emerald-200 ring-1 ring-emerald-300/20">
                            <x-icon name="heroicon-o-radio" class="h-7 w-7" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-[11px] font-semibold uppercase tracking-[0.25em] text-slate-400">{{ __('Now playing') }}</div>
                            <div class="mt-1 truncate text-base font-semibold text-white sm:text-lg" data-radio-current>{{ __('Choose a station') }}</div>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-white/10 bg-white/5 p-3" wire:ignore>
                        <audio class="w-full" controls playsinline preload="none" data-radio-audio>
                            {{ __('Your browser does not support the audio element.') }}
                        </audio>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button type="button" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-emerald-300/40 hover:bg-emerald-300/10 disabled:cursor-not-allowed disabled:opacity-40" data-radio-previous disabled>
                            <x-icon name="heroicon-o-chevron-left" class="h-4 w-4 rtl:rotate-180" />
                            <span>{{ __('Previous') }}</span>
                        </button>
                        <button type="button" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-emerald-300/40 hover:bg-emerald-300/10 disabled:cursor-not-allowed disabled:opacity-40" data-radio-next disabled>
                            <span>{{ __('Next') }}</span>
                            <x-icon name="heroicon-o-chevron-right" class="h-4 w-4 rtl:rotate-180" />
                        </button>
                    </div>

                    <div class="mt-4 min-h-6 text-sm text-slate-400" data-radio-status role="status" aria-live="polite">
                        {{ __('Loading radio stations...') }}
                    </div>

                    <button type="button" class="mt-3 hidden min-h-10 items-center justify-center rounded-full border border-amber-300/30 bg-amber-300/10 px-4 py-2 text-sm font-medium text-amber-100 transition hover:bg-amber-300/20" data-radio-retry>
                        {{ __('Try again') }}
                    </button>
                </div>

                <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <label class="block flex-1">
                            <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('Search radio stations') }}</span>
                            <span class="relative block">
                                <x-icon name="heroicon-o-magnifying-glass" class="pointer-events-none absolute start-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-500" />
                                <input type="search" class="min-h-12 w-full rounded-2xl border border-white/10 bg-[#021012] py-3 pe-4 ps-12 text-sm text-white outline-none placeholder:text-slate-600 focus:border-emerald-300/50 focus:ring-2 focus:ring-emerald-300/10" placeholder="{{ __('Search by station name...') }}" data-radio-search>
                            </span>
                        </label>
                        <div class="text-sm text-slate-400" data-radio-count></div>
                    </div>

                    <div class="mt-4 max-h-[25rem] space-y-2 overflow-y-auto pe-1" data-radio-list role="list" aria-label="{{ __('Radio stations') }}"></div>
                </div>
            </div>
        </div>
    </div>
</section>
