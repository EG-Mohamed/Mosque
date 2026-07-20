<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\KhutbaController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\PrayerTimeController;
use App\Http\Controllers\Api\SpecialPrayerController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::get('prayer-times', [PrayerTimeController::class, 'index'])->name('prayer-times.index');
    Route::get('prayer-times/today', [PrayerTimeController::class, 'today'])->name('prayer-times.today');

    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news-categories', [NewsController::class, 'categories'])->name('news-categories.index');
    Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');

    Route::get('khutbas', [KhutbaController::class, 'index'])->name('khutbas.index');
    Route::get('khutba-categories', [KhutbaController::class, 'categories'])->name('khutba-categories.index');
    Route::get('khutbas/{slug}', [KhutbaController::class, 'show'])->name('khutbas.show');

    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/{event}', [EventController::class, 'show'])->name('events.show');

    Route::get('special-prayers', [SpecialPrayerController::class, 'index'])->name('special-prayers.index');

    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');

    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
});
