<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IslamicLibraryController;
use App\Http\Controllers\KhutbaController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PrayerTimeController;
use App\Http\Controllers\QuranPlayerController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::middleware('section-enabled:prayer_times')->group(function (): void {
    Route::get('/prayer-times', [PrayerTimeController::class, 'index'])->name('prayer-times.index');
});

Route::middleware('section-enabled:events')->group(function (): void {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

Route::middleware('section-enabled:announcements')->group(function (): void {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
});

Route::middleware('section-enabled:news')->group(function (): void {
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
});

Route::middleware('section-enabled:gallery')->group(function (): void {
    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
});

Route::middleware('section-enabled:library')->group(function (): void {
    Route::get('/islamic-library', [IslamicLibraryController::class, 'index'])->name('islamic-library.index');
});

Route::middleware('section-enabled:khutba')->group(function (): void {
    Route::get('/khutba', [KhutbaController::class, 'index'])->name('khutba.index');
    Route::get('/khutba/{slug}', [KhutbaController::class, 'show'])->name('khutba.show');
});

Route::middleware('section-enabled:staff')->group(function (): void {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
});

Route::middleware('section-enabled:contact')->group(function (): void {
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
});

Route::prefix('api/quran-player')->name('quran-player.')->group(function (): void {
    Route::get('/bootstrap', [QuranPlayerController::class, 'bootstrap'])->name('bootstrap');
    Route::get('/radios', [QuranPlayerController::class, 'radios'])->name('radios');
    Route::get('/ayat-timing/soar', [QuranPlayerController::class, 'timingSoar'])->name('timing.soar');
    Route::get('/ayat-timing', [QuranPlayerController::class, 'ayatTiming'])->name('timing.ayat');
    Route::get('/page-svg', [QuranPlayerController::class, 'pageSvg'])->name('page-svg');
});
