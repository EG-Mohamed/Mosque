<?php

namespace App\Services\Mp3Quran;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class Mp3QuranClient
{
    private const STATIC_TTL = 86400;

    private const DYNAMIC_TTL = 3600;

    private const TIMING_TTL = 2592000;

    private const STALE_TTL = 2592000;

    private const LANGUAGE_MAP = [
        'ar' => 'ar',
        'en' => 'eng',
        'eng' => 'eng',
        'fr' => 'fr',
        'ru' => 'ru',
        'de' => 'de',
        'es' => 'es',
        'tr' => 'tr',
        'zh' => 'cn',
        'cn' => 'cn',
        'th' => 'th',
        'ur' => 'ur',
        'bn' => 'bn',
        'bs' => 'bs',
        'ug' => 'ug',
        'fa' => 'fa',
        'tg' => 'tg',
        'ml' => 'ml',
        'tl' => 'tl',
        'id' => 'id',
        'pt' => 'pt',
        'ha' => 'ha',
        'sw' => 'sw',
        'pl' => 'eng',
    ];

    public function bootstrap(array $config = []): array
    {
        $language = $this->resolveLanguage($config['language'] ?? null);
        $surahs = $this->surahs($language);
        $riwayat = $this->riwayat($language);
        $timingReads = $this->timingReads();

        $reciters = $this->reciters([
            'language' => $language,
            'reciter' => $config['default_reciter_id'] ?? null,
            'rewaya' => $config['default_riwayah_id'] ?? null,
            'sura' => $config['default_surah_id'] ?? null,
        ]);

        if ($reciters === []) {
            $reciters = $this->reciters(['language' => $language]);
        }

        return [
            'meta' => [
                'language' => $language,
                'generated_at' => now()->toIso8601String(),
            ],
            'catalogs' => [
                'suwar' => $surahs,
                'riwayat' => $riwayat,
                'timing_reads' => $timingReads,
            ],
            'data' => [
                'reciters' => $reciters,
            ],
            'defaults' => [
                'reciter_id' => $this->nullableInt($config['default_reciter_id'] ?? null),
                'riwayah_id' => $this->nullableInt($config['default_riwayah_id'] ?? null),
                'surah_id' => $this->nullableInt($config['default_surah_id'] ?? null),
            ],
        ];
    }

    public function languages(): array
    {
        return $this->listFromPayload(
            $this->request('languages', ttl: self::STATIC_TTL),
            'language',
        );
    }

    public function surahs(?string $language = null): array
    {
        return collect($this->listFromPayload(
            $this->request('suwar', ['language' => $this->resolveLanguage($language)], self::STATIC_TTL),
            'suwar',
        ))
            ->map(fn (array $surah): array => [
                'id' => (int) ($surah['id'] ?? 0),
                'name' => trim((string) ($surah['name'] ?? '')),
                'start_page' => $surah['start_page'] ?? null,
                'end_page' => $surah['end_page'] ?? null,
                'makkia' => $surah['makkia'] ?? null,
                'type' => $surah['type'] ?? null,
            ])
            ->filter(fn (array $surah): bool => $surah['id'] > 0)
            ->values()
            ->all();
    }

    public function riwayat(?string $language = null): array
    {
        return $this->listFromPayload(
            $this->request('riwayat', ['language' => $this->resolveLanguage($language)], self::STATIC_TTL),
            'riwayat',
        );
    }

    public function moshaf(?string $language = null): array
    {
        return $this->listFromPayload(
            $this->request('moshaf', ['language' => $this->resolveLanguage($language)], self::STATIC_TTL),
            'moshaf',
        );
    }

    public function videoTypes(?string $language = null): array
    {
        return $this->listFromPayload(
            $this->request('video_types', ['language' => $this->resolveLanguage($language)], self::STATIC_TTL),
            'video_types',
        );
    }

    public function reciters(array $filters = []): array
    {
        $language = $this->resolveLanguage($filters['language'] ?? null);
        $surahIndex = collect($this->surahs($language))->keyBy('id');
        $moshafIndex = collect($this->moshaf($language))->keyBy('id');
        $timingReadIndex = collect($this->timingReads())->keyBy(
            fn (array $read): string => $this->normalizeServerUrl((string) ($read['folder_url'] ?? ''))
        );

        $payload = $this->request('reciters', array_filter([
            'language' => $language,
            'reciter' => $this->nullableInt($filters['reciter'] ?? null),
            'rewaya' => $this->nullableInt($filters['rewaya'] ?? null),
            'sura' => $this->nullableInt($filters['sura'] ?? null),
            'last_updated_date' => $filters['last_updated_date'] ?? null,
        ], fn (mixed $value): bool => $value !== null && $value !== ''), self::DYNAMIC_TTL);

        return $this->normalizeReciters(
            $this->listFromPayload($payload, 'reciters'),
            $surahIndex,
            $moshafIndex,
            $timingReadIndex,
        );
    }

    public function recentReads(?string $language = null, ?int $limit = null): array
    {
        return $this->applyLimit(
            $this->recitersFromPayload(
                $this->request('recent_reads', ['language' => $this->resolveLanguage($language)], self::DYNAMIC_TTL),
                'reads',
                $language,
            ),
            $limit,
        );
    }

    public function radios(?string $language = null, ?int $limit = null): array
    {
        $language = $this->resolveLanguage($language);
        $additionalRadios = collect(config('services.mp3quran.additional_radios', []));

        try {
            $upstreamRadios = $this->listFromPayload(
                $this->request('radios', ['language' => $language], self::DYNAMIC_TTL),
                'radios',
            );
        } catch (Throwable $exception) {
            report($exception);
            $upstreamRadios = [];
        }

        $radios = $additionalRadios
            ->concat($upstreamRadios)
            ->filter(fn (mixed $radio): bool => is_array($radio))
            ->map(fn (array $radio): array => [
                'id' => (int) ($radio['id'] ?? 0),
                'name' => trim((string) (is_array($radio['name'] ?? null)
                    ? ($radio['name'][$language] ?? $radio['name']['ar'] ?? collect($radio['name'])->first())
                    : ($radio['name'] ?? ''))),
                'url' => $radio['url'] ?? null,
            ])
            ->filter(fn (array $radio): bool => $radio['id'] > 0 && filled($radio['url']))
            ->unique('url')
            ->values()
            ->all();

        return $this->applyLimit($radios, $limit);
    }

    public function tafasir(?string $language = null): array
    {
        return collect($this->listFromPayload(
            $this->request('tafasir', ['language' => $this->resolveLanguage($language)], self::STATIC_TTL),
            'tafasir',
        ))
            ->map(fn (array $tafsir): array => [
                'id' => (int) ($tafsir['id'] ?? 0),
                'name' => trim((string) ($tafsir['name'] ?? '')),
                'url' => $tafsir['url'] ?? null,
            ])
            ->filter(fn (array $tafsir): bool => $tafsir['id'] > 0)
            ->values()
            ->all();
    }

    public function tafsir(int $tafsirId, ?string $language = null, ?int $surah = null): array
    {
        $payload = $this->request('tafsir', array_filter([
            'tafsir' => $tafsirId,
            'sura' => $surah,
            'language' => $this->resolveLanguage($language),
        ], fn (mixed $value): bool => $value !== null && $value !== ''), self::DYNAMIC_TTL);

        return collect($this->extractTafsirItems($payload))
            ->map(fn (array $item): array => [
                'id' => (int) ($item['id'] ?? $item['sura_id'] ?? 0),
                'tafsir_id' => (int) ($item['tafsir_id'] ?? $tafsirId),
                'name' => trim((string) ($item['name'] ?? '')),
                'url' => $item['url'] ?? null,
                'sura_id' => (int) ($item['sura_id'] ?? $item['id'] ?? 0),
            ])
            ->filter(fn (array $item): bool => $item['sura_id'] > 0 && filled($item['url']))
            ->values()
            ->all();
    }

    public function tadabor(?string $language = null, ?int $surah = null, ?int $limit = null): array
    {
        $payload = $this->request('tadabor', array_filter([
            'language' => $this->resolveLanguage($language),
            'sura' => $surah,
        ], fn (mixed $value): bool => $value !== null && $value !== ''), self::DYNAMIC_TTL);

        $items = collect($payload['tadabor'] ?? [])
            ->flatMap(function (mixed $group, mixed $key): array {
                if (! is_array($group)) {
                    return [];
                }

                return collect($group)
                    ->filter(fn (mixed $item): bool => is_array($item))
                    ->map(function (array $item) use ($key): array {
                        $item['sura_id'] = (int) ($item['sura_id'] ?? $key);

                        return $item;
                    })
                    ->all();
            })
            ->map(fn (array $item): array => [
                'id' => (int) ($item['id'] ?? 0),
                'sura_id' => (int) ($item['sura_id'] ?? 0),
                'audio_url' => $item['audio_url'] ?? null,
                'image_url' => $item['image_url'] ?? null,
                'text' => trim((string) ($item['text'] ?? '')),
                'sora_name' => trim((string) ($item['sora_name'] ?? '')),
                'rewaya_name' => trim((string) ($item['rewaya_name'] ?? '')),
                'reciter_name' => trim((string) ($item['reciter_name'] ?? '')),
            ])
            ->filter(fn (array $item): bool => $item['id'] > 0)
            ->values()
            ->all();

        return $this->applyLimit($items, $limit);
    }

    public function videos(?string $language = null, ?int $limit = null): array
    {
        $videoTypes = collect($this->videoTypes($language))->keyBy('id');

        $videos = collect($this->listFromPayload(
            $this->request('videos', ['language' => $this->resolveLanguage($language)], self::DYNAMIC_TTL),
            'videos',
        ))
            ->map(function (array $group) use ($videoTypes): array {
                return [
                    'id' => (int) ($group['id'] ?? 0),
                    'reciter_name' => trim((string) ($group['reciter_name'] ?? '')),
                    'videos' => collect($group['videos'] ?? [])
                        ->map(function (array $video) use ($videoTypes): array {
                            $typeId = (int) ($video['video_type'] ?? 0);

                            return [
                                'id' => (int) ($video['id'] ?? 0),
                                'video_type' => $typeId,
                                'video_type_name' => $videoTypes->get($typeId, [])['video_type'] ?? null,
                                'video_url' => $video['video_url'] ?? null,
                                'video_thumb_url' => $video['video_thumb_url'] ?? null,
                            ];
                        })
                        ->filter(fn (array $video): bool => $video['id'] > 0 && filled($video['video_url']))
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn (array $group): bool => $group['id'] > 0 && $group['videos'] !== [])
            ->values()
            ->all();

        return $this->applyLimit($videos, $limit);
    }

    public function liveTv(?string $language = null): array
    {
        return collect($this->listFromPayload(
            $this->request('live-tv', ['language' => $this->resolveLanguage($language)], self::DYNAMIC_TTL),
            'livetv',
        ))
            ->map(fn (array $channel): array => [
                'id' => (int) ($channel['id'] ?? 0),
                'name' => trim((string) ($channel['name'] ?? '')),
                'url' => $channel['url'] ?? null,
            ])
            ->filter(fn (array $channel): bool => $channel['id'] > 0 && filled($channel['url']))
            ->values()
            ->all();
    }

    public function timingReads(): array
    {
        return collect($this->request('ayat_timing/reads', ttl: self::STATIC_TTL))
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $read): array => [
                'id' => (int) ($read['id'] ?? 0),
                'name' => trim((string) ($read['name'] ?? '')),
                'rewaya' => trim((string) ($read['rewaya'] ?? '')),
                'folder_url' => rtrim((string) ($read['folder_url'] ?? ''), '/').'/',
                'soar_count' => (int) ($read['soar_count'] ?? 0),
                'soar_link' => $read['soar_link'] ?? null,
            ])
            ->filter(fn (array $read): bool => $read['id'] > 0)
            ->values()
            ->all();
    }

    public function timingSoar(int $readId): array
    {
        return collect($this->request('ayat_timing/soar', ['read' => $readId], self::STATIC_TTL))
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $surah): array => [
                'id' => (int) ($surah['id'] ?? 0),
                'name' => trim((string) ($surah['name'] ?? '')),
                'timing_link' => $surah['timing_link'] ?? null,
            ])
            ->filter(fn (array $surah): bool => $surah['id'] > 0)
            ->values()
            ->all();
    }

    public function ayatTiming(int $readId, int $surah): array
    {
        return collect($this->request('ayat_timing', [
            'read' => $readId,
            'surah' => $surah,
        ], self::TIMING_TTL))
            ->filter(fn (mixed $item): bool => is_array($item))
            ->map(fn (array $item): array => [
                'ayah' => (int) ($item['ayah'] ?? 0),
                'polygon' => $item['polygon'] ?? null,
                'start_time' => (int) ($item['start_time'] ?? 0),
                'end_time' => (int) ($item['end_time'] ?? 0),
                'x' => $item['x'] ?? null,
                'y' => $item['y'] ?? null,
                'page' => $item['page'] ?? null,
            ])
            ->values()
            ->all();
    }

    public function fetchPageSvg(string $url): string
    {
        if (! Str::startsWith($url, 'https://www.mp3quran.net/api/quran_pages_svg/')) {
            throw new RuntimeException('Unsupported Quran page URL.');
        }

        $cacheKey = 'mp3quran_page_svg_'.md5($url);

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($url): string {
            $response = Http::timeout((int) config('services.mp3quran.timeout', 20))
                ->retry(2, 250)
                ->get($url);

            $response->throw();

            return (string) $response->body();
        });
    }

    public function resolveLanguage(?string $language = null): string
    {
        $language ??= app()->getLocale();

        return self::LANGUAGE_MAP[$language] ?? 'ar';
    }

    private function request(string $endpoint, array $query = [], int $ttl = self::STATIC_TTL): array
    {
        $query = array_filter($query, fn (mixed $value): bool => $value !== null && $value !== '' && $value !== []);
        ksort($query);

        $cacheKey = 'mp3quran_'.md5($endpoint.'|'.json_encode($query));
        $staleKey = $cacheKey.'_stale';

        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $response = Http::baseUrl((string) config('services.mp3quran.base_url', 'https://mp3quran.net/api/v3'))
                ->acceptJson()
                ->timeout((int) config('services.mp3quran.timeout', 20))
                ->retry(2, 250)
                ->get($endpoint, $query);

            $response->throw();

            $data = $response->json();

            if (! is_array($data)) {
                throw new RuntimeException("Unexpected payload for endpoint [{$endpoint}].");
            }

            Cache::put($cacheKey, $data, now()->addSeconds($ttl));
            Cache::put($staleKey, $data, now()->addSeconds(self::STALE_TTL));

            return $data;
        } catch (Throwable $exception) {
            $stale = Cache::get($staleKey);

            if (is_array($stale)) {
                return $stale;
            }

            throw $exception;
        }
    }

    private function listFromPayload(array $payload, string $key): array
    {
        $items = $payload[$key] ?? [];

        return is_array($items) ? array_values(array_filter($items, 'is_array')) : [];
    }

    private function recitersFromPayload(array $payload, string $key, ?string $language = null): array
    {
        $language = $this->resolveLanguage($language);
        $surahIndex = collect($this->surahs($language))->keyBy('id');
        $moshafIndex = collect($this->moshaf($language))->keyBy('id');
        $timingReadIndex = collect($this->timingReads())->keyBy(
            fn (array $read): string => $this->normalizeServerUrl((string) ($read['folder_url'] ?? ''))
        );

        return collect($this->normalizeReciters(
            $this->listFromPayload($payload, $key),
            $surahIndex,
            $moshafIndex,
            $timingReadIndex,
        ))
            ->map(fn (array $item): array => [
                ...$item,
                'recent_date' => $item['recent_date'] ?? null,
            ])
            ->all();
    }

    private function normalizeReciters(array $items, $surahIndex, $moshafIndex, $timingReadIndex): array
    {
        return collect($items)
            ->map(function (array $reciter) use ($surahIndex, $moshafIndex, $timingReadIndex): array {
                return [
                    'id' => (int) ($reciter['id'] ?? 0),
                    'name' => trim((string) ($reciter['name'] ?? '')),
                    'letter' => $reciter['letter'] ?? null,
                    'date' => $reciter['date'] ?? null,
                    'recent_date' => $reciter['recent_date'] ?? null,
                    'moshaf' => collect($reciter['moshaf'] ?? [])
                        ->map(function (array $moshaf) use ($surahIndex, $moshafIndex, $timingReadIndex): array {
                            $surahIds = collect(explode(',', (string) ($moshaf['surah_list'] ?? '')))
                                ->map(fn (string $id): int => (int) trim($id))
                                ->filter(fn (int $id): bool => $id > 0)
                                ->values();

                            $server = rtrim((string) ($moshaf['server'] ?? ''), '/').'/';
                            $timingRead = $timingReadIndex->get($this->normalizeServerUrl($server));
                            $catalog = $moshafIndex->get((int) ($moshaf['moshaf_type'] ?? 0));

                            return [
                                'id' => (int) ($moshaf['id'] ?? 0),
                                'name' => trim((string) ($moshaf['name'] ?? '')),
                                'server' => $server,
                                'surah_total' => (int) ($moshaf['surah_total'] ?? 0),
                                'moshaf_type' => (int) ($moshaf['moshaf_type'] ?? 0),
                                'moshaf_name' => $catalog['name'] ?? null,
                                'surah_list' => $surahIds->all(),
                                'surahs' => $surahIds
                                    ->map(fn (int $surahId): array => [
                                        'id' => $surahId,
                                        'name' => $surahIndex->get($surahId, [])['name'] ?? __('Surah').' '.$surahId,
                                        'audio_url' => $this->buildSurahAudioUrl($server, $surahId),
                                    ])
                                    ->values()
                                    ->all(),
                                'timing_read_id' => $timingRead['id'] ?? null,
                                'timing_supported' => isset($timingRead['id']),
                            ];
                        })
                        ->filter(fn (array $moshaf): bool => filled($moshaf['server']) && $moshaf['surahs'] !== [])
                        ->values()
                        ->all(),
                ];
            })
            ->filter(fn (array $reciter): bool => $reciter['id'] > 0 && $reciter['moshaf'] !== [])
            ->values()
            ->all();
    }

    private function extractTafsirItems(array $payload): array
    {
        $tafasir = $payload['tafasir'] ?? $payload['tafsir'] ?? $payload;

        if (isset($tafasir['sora']) && is_array($tafasir['sora'])) {
            return collect($tafasir['sora'])
                ->flatMap(fn (mixed $items): array => is_array($items) ? $items : [])
                ->filter(fn (mixed $item): bool => is_array($item))
                ->values()
                ->all();
        }

        if (is_array($tafasir) && array_is_list($tafasir)) {
            return array_values(array_filter($tafasir, 'is_array'));
        }

        return [];
    }

    private function buildSurahAudioUrl(string $server, int $surahId): string
    {
        return rtrim($server, '/').'/'.str_pad((string) $surahId, 3, '0', STR_PAD_LEFT).'.mp3';
    }

    private function normalizeServerUrl(string $url): string
    {
        return Str::lower(rtrim($url, '/')).'/';
    }

    private function applyLimit(array $items, ?int $limit = null): array
    {
        if ($limit === null || $limit < 1) {
            return $items;
        }

        return array_slice($items, 0, $limit);
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $int = (int) $value;

        return $int > 0 ? $int : null;
    }
}
