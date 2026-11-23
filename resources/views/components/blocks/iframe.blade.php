@php
    $data = $block['data'] ?? $data ?? [];
    $url = trim((string) ($data['url'] ?? ''));
    $parsedUrl = $url !== '' ? parse_url($url) : false;
    $scheme = is_array($parsedUrl) ? strtolower((string) ($parsedUrl['scheme'] ?? '')) : '';
    $sanitizedUrl = in_array($scheme, ['http', 'https'], true) ? $url : '';
    $height = (int) ($data['height'] ?? 450);
    $height = $height > 0 ? $height : 450;
    $maxWidth = match ($data['max_width'] ?? '7xl') {
        'full' => 'w-full',
        '6xl' => 'max-w-6xl',
        '5xl' => 'max-w-5xl',
        '4xl' => 'max-w-4xl',
        '3xl' => 'max-w-3xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-7xl',
    };
    $hasRoundedCorners = (bool) ($data['border_radius'] ?? true);
    $shouldLazyLoad = (bool) ($data['lazy_load'] ?? true);
    $title = trim((string) ($data['title'] ?? ''));
    $iframeTitle = $title !== '' ? $title : __('Embedded Content');
    $loading = $shouldLazyLoad ? 'lazy' : 'eager';
@endphp

@if($sanitizedUrl !== '')
    <section class="bg-white py-16">
        <div class="{{ $maxWidth }} mx-auto px-6">
            @if($title !== '')
                <div class="mb-6 text-center">
                    <h2 class="text-3xl font-bold text-neutral-900 md:text-4xl">{{ $title }}</h2>
                </div>
            @endif

            <div data-consent-embed class="relative overflow-hidden border border-neutral-200 bg-white shadow-sm {{ $hasRoundedCorners ? 'rounded-2xl' : '' }}">
                <x-consent-placeholder :provider="parse_url($sanitizedUrl, PHP_URL_HOST)" />
                <iframe
                    data-consent-category="media"
                    data-consent-src="{{ $sanitizedUrl }}"
                    title="{{ $iframeTitle }}"
                    class="w-full"
                    height="{{ $height }}"
                    style="border: 0;"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                    loading="{{ $loading }}"
                ></iframe>
            </div>
        </div>
    </section>
@endif
