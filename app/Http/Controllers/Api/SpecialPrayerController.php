<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SpecialPrayerIndexRequest;
use App\Http\Resources\SpecialPrayerResource;
use App\Models\SpecialPrayer;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecialPrayerController extends Controller
{
    public function index(SpecialPrayerIndexRequest $request): AnonymousResourceCollection
    {
        $query = SpecialPrayer::query()->orderBy('date')->orderBy('time');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->string('to'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        return SpecialPrayerResource::collection($query->paginate($request->perPage(15)));
    }
}
