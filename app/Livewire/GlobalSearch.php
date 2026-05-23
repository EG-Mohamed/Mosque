<?php

namespace App\Livewire;

use App\Models\Khutba;
use App\Models\Language;
use App\Models\News;
use App\Support\PublicNavigation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    #[Computed]
    public function results(): array
    {
        $term = trim($this->query);

        if (mb_strlen($term) < 2) {
            return ['news' => collect(), 'khutab' => collect()];
        }

        $locales = Language::active()->pluck('code')->all();
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
        $locale = app()->getLocale();

        $news = collect();

        if (PublicNavigation::isEnabled('news')) {
            [$scoreSql, $scoreBindings] = $this->relevanceSql($escaped, $locales, ['title'], ['excerpt']);

            $news = News::query()
                ->published()
                ->where(fn (Builder $q) => $this->applySearch($q, $escaped, $locales, ['title', 'excerpt', 'content']))
                ->selectRaw('*, '.$scoreSql.' as _score', $scoreBindings)
                ->orderByDesc('_score')
                ->limit(5)
                ->get()
                ->map(fn ($model) => $this->shape($model, $term, $locale, $locales, ['title', 'excerpt', 'content'], 'news'));
        }

        $khutab = collect();

        if (PublicNavigation::isEnabled('khutba')) {
            [$scoreSql, $scoreBindings] = $this->relevanceSql($escaped, $locales, ['title', 'topic'], ['summary', 'speaker']);

            $khutab = Khutba::query()
                ->published()
                ->whereNotNull('slug')
                ->where(fn (Builder $q) => $this->applySearch($q, $escaped, $locales, ['title', 'topic', 'summary', 'speaker', 'content']))
                ->selectRaw('*, '.$scoreSql.' as _score', $scoreBindings)
                ->orderByDesc('_score')
                ->limit(5)
                ->get()
                ->map(fn ($model) => $this->shape($model, $term, $locale, $locales, ['title', 'topic', 'summary', 'speaker', 'content'], 'khutba'));
        }

        return ['news' => $news, 'khutab' => $khutab];
    }

    private function applySearch(Builder $query, string $escaped, array $locales, array $fields): void
    {
        $query->where(function (Builder $q) use ($escaped, $locales, $fields): void {
            foreach ($fields as $field) {
                foreach ($locales as $locale) {
                    $q->orWhereRaw(
                        "LOWER(JSON_UNQUOTE(JSON_EXTRACT({$field}, '$.{$locale}'))) LIKE LOWER(?)",
                        ["%{$escaped}%"]
                    );
                }
            }
        });
    }

    private function relevanceSql(string $escaped, array $locales, array $titleFields, array $secondaryFields): array
    {
        $cases = [];
        $bindings = [];
        $term = '%'.$escaped.'%';

        foreach ($locales as $locale) {
            foreach ($titleFields as $field) {
                $cases[] = "WHEN LOWER(JSON_UNQUOTE(JSON_EXTRACT({$field}, '$.{$locale}'))) LIKE LOWER(?) THEN 100";
                $bindings[] = $term;
            }
            foreach ($secondaryFields as $field) {
                $cases[] = "WHEN LOWER(JSON_UNQUOTE(JSON_EXTRACT({$field}, '$.{$locale}'))) LIKE LOWER(?) THEN 50";
                $bindings[] = $term;
            }
        }

        return ['CASE '.implode(' ', $cases).' ELSE 10 END', $bindings];
    }

    private function shape($model, string $term, string $locale, array $locales, array $fields, string $type): array
    {
        $url = $type === 'khutba'
            ? route('khutba.show', $model->slug)
            : route('news.show', $model->slug);

        $title = $model->getTranslation('title', $locale, false) ?: $model->title;

        return [
            'url' => $url,
            'title' => $title,
            'snippetHtml' => $this->buildSnippet($model, $term, $locale, $locales, $fields),
        ];
    }

    private function buildSnippet($model, string $term, string $locale, array $locales, array $fields): string
    {
        $termLower = mb_strtolower($term);
        $searchLocales = array_unique(array_merge([$locale], $locales));

        foreach ($fields as $field) {
            foreach ($searchLocales as $searchLocale) {
                $text = strip_tags((string) $model->getTranslation($field, $searchLocale, false));
                $lower = mb_strtolower($text);
                $pos = mb_strpos($lower, $termLower);

                if ($pos === false) {
                    continue;
                }

                $start = max(0, $pos - 50);
                $raw = mb_substr($text, $start, 120);

                if ($start > 0) {
                    $raw = '...'.ltrim($raw);
                }

                $matchStart = mb_strpos(mb_strtolower($raw), $termLower);

                if ($matchStart === false) {
                    return e($raw);
                }

                $before = mb_substr($raw, 0, $matchStart);
                $match = mb_substr($raw, $matchStart, mb_strlen($term));
                $after = mb_substr($raw, $matchStart + mb_strlen($term));

                return e($before).'<mark class="bg-emerald-100 text-emerald-800 rounded px-0.5">'.e($match).'</mark>'.e($after);
            }
        }

        return '';
    }

    public function render(): View
    {
        return view('livewire.global-search');
    }
}
