<?php

namespace App\Support;

class ConsentGate
{
    public const string AnalyticsCategory = 'analytics';

    public static function scripts(?string $html, string $category = self::AnalyticsCategory): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $html = (string) preg_replace('#<noscript\b[^>]*>.*?</noscript>#is', '', $html);

        return (string) preg_replace_callback(
            '#<script\b([^>]*)>#i',
            function (array $matches) use ($category): string {
                $attributes = $matches[1];
                $isModule = (bool) preg_match('#\btype\s*=\s*(["\']?)module\1#i', $attributes);
                $attributes = (string) preg_replace('#\s(type|data-category|data-type)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $attributes);
                $moduleAttribute = $isModule ? ' data-type="module"' : '';

                return '<script type="text/plain" data-category="'.e($category).'"'.$moduleAttribute.$attributes.'>';
            },
            $html,
        );
    }
}
