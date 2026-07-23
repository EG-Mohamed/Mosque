<?php

use App\Filament\Admin\Blocks\OnlineRadioBlock;
use App\Models\Page;
use Redberry\PageBuilderPlugin\Models\PageBuilderBlock;

it('renders the online radio block configuration on a published page', function () {
    $page = Page::query()->create([
        'title' => [
            'en' => 'Radio Page',
            'ar' => 'صفحة الإذاعة',
        ],
        'slug' => 'radio-page',
        'is_published' => true,
        'show_in_nav' => false,
    ]);

    $radioBlock = PageBuilderBlock::query()->create([
        'block_type' => OnlineRadioBlock::class,
        'page_builder_blockable_type' => Page::class,
        'page_builder_blockable_id' => $page->id,
        'data' => [
            'title' => [
                'en' => 'Live Quran Radio',
                'ar' => 'إذاعة القرآن المباشرة',
            ],
            'intro' => [
                'en' => 'Browse and listen to live Quran stations.',
                'ar' => 'تصفح واستمع إلى إذاعات القرآن المباشرة.',
            ],
            'language_mode' => 'eng',
        ],
        'order' => 1,
    ]);

    $this->get(route('page.show', $page->slug))
        ->assertOk()
        ->assertSeeText('Live Quran Radio')
        ->assertSeeText('Browse and listen to live Quran stations.')
        ->assertSee('data-online-radio=', false)
        ->assertSee('online-radio-'.$radioBlock->id, false)
        ->assertSee(str_replace('/', '\\/', route('quran-player.radios')), false)
        ->assertSee('"language":"eng"', false);
});
