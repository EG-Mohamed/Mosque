@php
    $navLinks = array_filter([
        ['route' => 'home', 'label' => __('Home')],

        \App\Support\PublicNavigation::isEnabled('prayer_times')
            ? ['route' => 'prayer-times.index', 'label' => __('Prayer Times')]
            : null,

        \App\Support\PublicNavigation::isEnabled('events') && \App\Models\Event::published()->exists()
            ? ['route' => 'events.index', 'label' => __('Events')]
            : null,

        \App\Support\PublicNavigation::isEnabled('announcements') && \App\Models\Announcement::active()->exists()
            ? ['route' => 'announcements.index', 'label' => __('Announcements')]
            : null,

        \App\Support\PublicNavigation::isEnabled('news')
            ? ['route' => 'news.index', 'label' => __('News')]
            : null,

        \App\Support\PublicNavigation::isEnabled('gallery') && \App\Models\MediaItem::exists()
            ? ['route' => 'gallery.index', 'label' => __('Gallery')]
            : null,

        \App\Support\PublicNavigation::isEnabled('library') && (\App\Models\QuranVerse::exists() || \App\Models\Hadith::exists())
            ? ['route' => 'islamic-library.index', 'label' => __('Library')]
            : null,

        \App\Support\PublicNavigation::isEnabled('khutba')
            ? ['route' => 'khutba.index', 'label' => __('Khutba')]
            : null,

        \App\Support\PublicNavigation::isEnabled('staff') && \App\Models\Staff::exists()
            ? ['route' => 'staff.index', 'label' => __('Staff')]
            : null,
    ]);

    $contactLink = \App\Support\PublicNavigation::isEnabled('contact')
        ? ['route' => 'contact.index', 'label' => __('Contact')]
        : null;

    $navPages = \App\Models\Page::nav()->where('is_home', false)->get();
    $languages = \App\Models\Language::active()->get();
@endphp

@php
    // Reusable link class resolver
    $linkClass = fn(bool $active, bool $block = false) => implode(' ', array_filter([
        'px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150',
        $block ? 'block' : '',
        $active
            ? 'text-emerald-600 bg-emerald-50'
            : 'text-neutral-600 hover:text-emerald-600 hover:bg-neutral-50',
    ]));

    $langClass = fn(bool $active) => implode(' ', [
        'px-2.5 py-1 rounded text-xs font-medium border transition-colors duration-150',
        $active
            ? 'bg-emerald-600 text-white border-emerald-600'
            : 'text-neutral-600 border-neutral-300 hover:border-emerald-500 hover:text-emerald-600',
    ]);
@endphp

<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ open: false }">
    <div class="mx-auto px-4 sm:px-6 lg:px-10">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center shrink-0 h-16 py-2">
                @if(setting('branding.logo'))
                    <img src="{{ \Illuminate\Support\Facades\Storage::url(setting('branding.logo')) }}"
                         alt="{{ setting('general.name') }}"
                         class="h-15 w-auto object-contain">
                @endif
            </a>

            {{-- Desktop Links --}}
            <div class="hidden lg:flex items-center gap-1">
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="{{ $linkClass(request()->routeIs($link['route'])) }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach

                @foreach($navPages as $page)
                    <a href="{{ route('page.show', $page->slug) }}" class="{{ $linkClass(request()->is('page/' . $page->slug)) }}">
                        {{ $page->title }}
                    </a>
                @endforeach

                @if($contactLink)
                    <a href="{{ route($contactLink['route']) }}" class="{{ $linkClass(request()->routeIs($contactLink['route'])) }}">
                        {{ $contactLink['label'] }}
                    </a>
                @endif
            </div>

            {{-- Global Search --}}
            <div class="hidden lg:flex items-center">
                @livewire('global-search')
            </div>

            {{-- Language Switcher --}}
            @if($languages->isNotEmpty())
                <div class="hidden lg:flex items-center gap-2">
                    @foreach($languages as $language)
                        <a href="{{ request()->fullUrlWithQuery(['lang' => $language->code]) }}"
                           class="{{ $langClass(app()->getLocale() === $language->code) }}">
                            {{ strtoupper($language->code) }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Mobile Toggle --}}
            <button @click="open = !open"
                    class="lg:hidden p-2 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                    :aria-expanded="open">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lg:hidden border-t border-neutral-100 bg-white"
         @click.away="open = false">
        <div class="px-4 py-3 space-y-1">
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}" class="{{ $linkClass(request()->routeIs($link['route']), true) }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            @foreach($navPages as $page)
                <a href="{{ route('page.show', $page->slug) }}" class="{{ $linkClass(request()->is('page/' . $page->slug), true) }}">
                    {{ $page->title }}
                </a>
            @endforeach

            @if($contactLink)
                <a href="{{ route($contactLink['route']) }}" class="{{ $linkClass(request()->routeIs($contactLink['route']), true) }}">
                    {{ $contactLink['label'] }}
                </a>
            @endif

            <div class="pt-3 border-t border-neutral-100">
                @livewire('global-search')
            </div>

            @if($languages->isNotEmpty())
                <div class="pt-3 border-t border-neutral-100 flex items-center gap-2 flex-wrap">
                    @foreach($languages as $language)
                        <a href="{{ request()->fullUrlWithQuery(['lang' => $language->code]) }}"
                           class="{{ $langClass(app()->getLocale() === $language->code) }}">
                            {{ strtoupper($language->code) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</nav>