<?php

use App\Models\News;

it('uses the locale middleware for api translations and localized search', function () {
    News::query()->create([
        'title' => ['en' => 'Community update', 'ar' => 'تحديث المجتمع'],
        'slug' => 'community-update',
        'excerpt' => ['en' => 'English excerpt', 'ar' => 'ملخص عربي'],
        'content' => ['en' => 'English content', 'ar' => 'محتوى عربي'],
        'is_published' => true,
        'published_at' => now()->subMinute(),
    ]);

    $this->withHeader('Accept-locale', 'ar')
        ->getJson(route('api.news.index', [
            'search' => 'تحديث',
            'locale' => 'en',
            'lang' => 'en',
        ]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'تحديث المجتمع')
        ->assertJsonPath('data.0.excerpt', 'ملخص عربي');
});

it('ignores locale query parameters when the locale header is absent', function () {
    News::query()->create([
        'title' => ['en' => 'English title', 'ar' => 'عنوان عربي'],
        'slug' => 'english-title',
        'is_published' => true,
        'published_at' => now()->subMinute(),
    ]);

    $this->getJson(route('api.news.index', ['locale' => 'ar', 'lang' => 'ar']))
        ->assertOk()
        ->assertJsonPath('data.0.title', 'English title');
});
