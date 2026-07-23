<?php

namespace App\Providers;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\Blocks\AnnouncementsBlock;
use App\Filament\Admin\Blocks\ContactFormBlock;
use App\Filament\Admin\Blocks\ContactMapBlock;
use App\Filament\Admin\Blocks\CounterBlock;
use App\Filament\Admin\Blocks\CtaBlock;
use App\Filament\Admin\Blocks\CustomHtmlBlock;
use App\Filament\Admin\Blocks\DonationBlock;
use App\Filament\Admin\Blocks\EventsBlock;
use App\Filament\Admin\Blocks\FaqBlock;
use App\Filament\Admin\Blocks\GalleryBlock;
use App\Filament\Admin\Blocks\HadithBlock;
use App\Filament\Admin\Blocks\HeroBlock;
use App\Filament\Admin\Blocks\IframeBlock;
use App\Filament\Admin\Blocks\IslamicCalendarBlock;
use App\Filament\Admin\Blocks\KhutbaArchiveBlock;
use App\Filament\Admin\Blocks\LiveStreamBlock;
use App\Filament\Admin\Blocks\NewsBlock;
use App\Filament\Admin\Blocks\OnlineRadioBlock;
use App\Filament\Admin\Blocks\PrayerTimesBlock;
use App\Filament\Admin\Blocks\QiblaDirectionBlock;
use App\Filament\Admin\Blocks\QuranPlayerBlock;
use App\Filament\Admin\Blocks\QuranVerseBlock;
use App\Filament\Admin\Blocks\RamadanScheduleBlock;
use App\Filament\Admin\Blocks\RichTextBlock;
use App\Filament\Admin\Blocks\SliderBlock;
use App\Filament\Admin\Blocks\SpacerBlock;
use App\Filament\Admin\Blocks\SpecialPrayersBlock;
use App\Filament\Admin\Blocks\StaffBlock;
use App\Filament\Admin\Blocks\TestimonialBlock;
use App\Filament\Admin\Blocks\VideoBlock;
use App\Filament\Admin\Blocks\ZakatCalculatorBlock;
use App\Models\Language;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Panel;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema as DatabaseSchema;
use Illuminate\Support\ServiceProvider;
use Redberry\PageBuilderPlugin\Components\Forms\PageBuilder;
use Statikbe\FilamentTranslationManager\FilamentTranslationManager;
use Throwable;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AppServiceProvider extends ServiceProvider
{
    private const FALLBACK_LOCALE_LABELS = [
        'ar' => 'العربية',
        'en' => 'English',
    ];

    public function register(): void
    {
        Panel::configureUsing(fn (Panel $panel) => $panel->maxContentWidth(Width::Full)
            ->font('Alexandria')
            ->colors([
                'primary' => Color::Emerald,
            ])
            ->readOnlyRelationManagersOnResourceViewPagesByDefault(false));

    }

    public function boot(): void
    {
        PhoneInput::configureUsing(fn (PhoneInput $phoneInput) => $phoneInput->initialCountry(function () {
            return rescue(fn () => Http::get('https://ipinfo.io/'.request()->ip().'/json')->json('country'), app()->getLocale(), report: false) ?? 'eg';

        }));
        TimePicker::configureUsing(fn (TimePicker $picker) => $picker->seconds(false));
        Model::automaticallyEagerLoadRelationships();
        Model::unguard();
        PageBuilder::configureUsing(fn (PageBuilder $pageBuilder) => $pageBuilder->blocks([
            HeroBlock::class,
            SliderBlock::class,
            PrayerTimesBlock::class,
            IslamicCalendarBlock::class,
            RamadanScheduleBlock::class,
            SpecialPrayersBlock::class,
            ZakatCalculatorBlock::class,
            QiblaDirectionBlock::class,
            EventsBlock::class,
            AnnouncementsBlock::class,
            QuranVerseBlock::class,
            QuranPlayerBlock::class,
            OnlineRadioBlock::class,
            HadithBlock::class,
            StaffBlock::class,
            GalleryBlock::class,
            NewsBlock::class,
            RichTextBlock::class,
            ContactMapBlock::class,
            CustomHtmlBlock::class,
            IframeBlock::class,
            SpacerBlock::class,
            KhutbaArchiveBlock::class,
            DonationBlock::class,
            TestimonialBlock::class,
            FaqBlock::class,
            CtaBlock::class,
            VideoBlock::class,
            LiveStreamBlock::class,
            CounterBlock::class,
            ContactFormBlock::class,
        ]));
        $locales = $this->resolveActiveLocales();
        TranslatableTabs::configureUsing(function (TranslatableTabs $component) use ($locales) {

            $component->localesLabels($locales)
                ->locales(array_keys($locales))
                ->addDirectionByLocale()
                ->addEmptyBadgeWhenAllFieldsAreEmpty(emptyLabel: __('Not Defined'))
                ->addSetActiveTabThatHasValue();
        });
        FilamentTranslationManager::setLocales(array_keys($locales));
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) use ($locales) {
            $switch
                ->locales(array_keys($locales));
        });
        Table::configureUsing(fn (Table $table) => $table->defaultDateTimeDisplayFormat('j M Y - g:i A')
            ->defaultDateDisplayFormat('j M Y')
            ->defaultTimeDisplayFormat('g:i A'));
        ToggleColumn::configureUsing(function (ToggleColumn $toggle): void {
            $toggle->offColor('danger')
                ->onColor('success')
                ->onIcon('fas-check-circle')
                ->offIcon('fas-times-circle');
        });
        Schema::configureUsing(fn (Schema $schema) => $schema->defaultDateTimeDisplayFormat('j M Y - g:i A')
            ->defaultDateDisplayFormat('j M Y')
            ->defaultTimeDisplayFormat('g:i A'));
        ViewAction::configureUsing(fn (ViewAction $action) => $action->button()
            ->size(Size::ExtraSmall));
        EditAction::configureUsing(fn (EditAction $action) => $action->button()
            ->size(Size::ExtraSmall));
        DeleteAction::configureUsing(fn (DeleteAction $action) => $action->button()
            ->size(Size::ExtraSmall));
        Column::configureUsing(fn (Column $column) => $column->translateLabel());
        Field::configureUsing(fn (Field $field) => $field->translateLabel());
        Entry::configureUsing(fn (Entry $entry) => $entry->translateLabel());
        BaseFilter::configureUsing(fn (BaseFilter $baseFilter) => $baseFilter->translateLabel());
        Action::configureUsing(fn (Action $action) => $action->size(Size::ExtraSmall));
        Section::configureUsing(fn (Section $section) => $section->columnSpanFull());
        ImageColumn::configureUsing(fn (ImageColumn $imageColumn) => $imageColumn->checkFileExistence(false)
            ->visibility('public')
            ->extraImgAttributes(['loading' => 'lazy']));
        ImageEntry::configureUsing(fn (ImageEntry $imageEntry) => $imageEntry->checkFileExistence(false)
            ->visibility('public')
            ->extraImgAttributes(['loading' => 'lazy']));
        FileUpload::configureUsing(fn (FileUpload $fileUpload) => $fileUpload->visibility('public')
            ->fetchFileInformation(false));
    }

    private function resolveActiveLocales(): array
    {
        $fallbackLocale = config('app.fallback_locale', config('app.locale', 'en'));
        $fallbackLocales = [
            $fallbackLocale => self::FALLBACK_LOCALE_LABELS[$fallbackLocale] ?? strtoupper($fallbackLocale),
        ];

        try {
            if (! DatabaseSchema::hasTable('languages')) {
                return $fallbackLocales;
            }

            $resolver = fn (): array => Language::query()
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->pluck('name', 'code')
                ->toArray();

            $locales = app()->runningUnitTests() || config('cache.default') === 'array'
                ? $resolver()
                : Cache::flexible('local', [43200, 86400], $resolver);
            //            dd($locales);

            return $locales !== [] ? $locales : $fallbackLocales;
        } catch (Throwable) {
            return $fallbackLocales;
        }
    }
}
