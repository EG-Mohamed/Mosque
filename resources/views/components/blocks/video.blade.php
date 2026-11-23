@php
    $data = $block['data'] ?? $data ?? [];
    $url = $data['video_url'] ?? '';
    $embedUrl = '';

    if (str_contains($url, 'youtube.com/watch')) {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        $embedUrl = 'https://www.youtube-nocookie.com/embed/' . ($params['v'] ?? '');
    } elseif (str_contains($url, 'youtu.be/')) {
        $embedUrl = 'https://www.youtube-nocookie.com/embed/' . basename(parse_url($url, PHP_URL_PATH));
    } elseif (str_contains($url, 'vimeo.com/')) {
        $embedUrl = 'https://player.vimeo.com/video/' . basename(parse_url($url, PHP_URL_PATH));
    }
@endphp

@if($embedUrl)
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-6">
            <div data-consent-embed class="relative aspect-video rounded-2xl overflow-hidden shadow-lg">
                <x-consent-placeholder :provider="str_contains($embedUrl, 'vimeo') ? 'Vimeo' : 'YouTube'" />
                <iframe data-consent-category="media" data-consent-src="{{ $embedUrl }}"
                        class="absolute inset-0 w-full h-full"
                        frameborder="0"
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                </iframe>
            </div>

            @if(!empty($data['caption']))
                <p class="text-center text-neutral-500 mt-4 text-sm">{{ $data['caption'] }}</p>
            @endif
        </div>
    </section>
@endif
