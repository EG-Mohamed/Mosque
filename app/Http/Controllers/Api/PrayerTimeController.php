<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PrayerTimeIndexRequest;
use App\Http\Resources\PrayerTimeResource;
use App\Models\PrayerTime;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PrayerTimeController extends Controller
{
    public function index(PrayerTimeIndexRequest $request): AnonymousResourceCollection
    {
        $query = PrayerTime::query()->orderBy('date');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->string('to'));
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->integer('year'));
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->integer('month'));
        }

        return PrayerTimeResource::collection($query->paginate($request->perPage(31)));
    }

    public function today(PrayerTimeIndexRequest $request): PrayerTimeResource
    {
        $date = $request->input('date', today()->toDateString());

        return new PrayerTimeResource(PrayerTime::query()->whereDate('date', $date)->firstOrFail());
    }
}
