@php
    $data = $block['data'] ?? $data ?? [];
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $streamUrl = $data['stream_url'] ?? '';
    $scheduleText = $data['schedule_text'] ?? '';
    $autoplay = $data['autoplay'] ?? false;

    $embedUrl = '';
    if ($streamUrl) {
        if (str_contains($streamUrl, 'youtube.com/watch')) {
            parse_str(parse_url($streamUrl, PHP_URL_QUERY), $params);
            $embedUrl = 'https://www.youtube-nocookie.com/embed/' . ($params['v'] ?? '');
        } elseif (str_contains($streamUrl, 'youtu.be/')) {
            $embedUrl = 'https://www.youtube-nocookie.com/embed/' . basename(parse_url($streamUrl, PHP_URL_PATH));
        } elseif (str_contains($streamUrl, 'youtube.com/live/')) {
            $embedUrl = 'https://www.youtube-nocookie.com/embed/' . basename(parse_url($streamUrl, PHP_URL_PATH));
        } elseif (str_contains($streamUrl, 'youtube.com/embed/')) {
            $embedUrl = $streamUrl;
        } elseif (str_contains($streamUrl, 'facebook.com')) {
            $embedUrl = 'https://www.facebook.com/plugins/video.php?href=' . urlencode($streamUrl) . '&show_text=false&width=560';
        } else {
            $embedUrl = $streamUrl;
        }

        if ($autoplay) {
            $embedUrl .= (str_contains($embedUrl, '?') ? '&' : '?') . 'autoplay=1';
        }
    }
@endphp

@if($embedUrl)
    <section class="relative py-8 sm:py-16 overflow-hidden bg-[#0d2137]">
        <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-600 via-emerald-400 to-emerald-600"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6">

            @if(!empty($title) || !empty($description))
                <div class="mb-6 sm:mb-8 text-center">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <div class="h-px w-8 bg-gradient-to-r from-transparent to-emerald-400"></div>
                        <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                        <div class="h-px w-8 bg-gradient-to-l from-transparent to-emerald-400"></div>
                    </div>
                    @if(!empty($title))
                        <h2 class="text-xl sm:text-2xl font-bold text-white tracking-wide">{{ $title }}</h2>
                    @endif
                    @if(!empty($description))
                        <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-2xl mx-auto leading-relaxed">{{ $description }}</p>
                    @endif
                </div>
            @endif

            <div data-consent-embed class="relative aspect-video rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10">
                <x-consent-placeholder :provider="str_contains($embedUrl, 'facebook.com') ? 'Facebook' : (str_contains($embedUrl, 'youtube') ? 'YouTube' : parse_url($embedUrl, PHP_URL_HOST))" />
                <iframe data-consent-category="media" data-consent-src="{{ $embedUrl }}"
                        class="absolute inset-0 w-full h-full"
                        frameborder="0"
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                </iframe>
            </div>

            @if(!empty($scheduleText))
                <div class="mt-4 sm:mt-6 text-center">
                    <div class="inline-flex items-center gap-2 bg-white/5 rounded-full px-4 py-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <span class="text-slate-300 text-xs sm:text-sm">{{ $scheduleText }}</span>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
