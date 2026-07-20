<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AnnouncementIndexRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnnouncementController extends Controller
{
    public function index(AnnouncementIndexRequest $request): AnonymousResourceCollection
    {
        $query = Announcement::query()->active();

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        return AnnouncementResource::collection($query->paginate($request->perPage(15)));
    }
}
