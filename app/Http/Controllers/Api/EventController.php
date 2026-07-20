<?php

namespace App\Http\Controllers\Api;

use App\Enums\EventStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EventIndexRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function index(EventIndexRequest $request): AnonymousResourceCollection
    {
        $query = Event::query()->published();

        if ($request->filled('from')) {
            $query->whereDate('starts_at', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('starts_at', '<=', $request->string('to'));
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        return EventResource::collection($query->paginate($request->perPage(12)));
    }

    public function show(Event $event): EventResource
    {
        abort_unless($event->status === EventStatus::Published, 404);

        return new EventResource($event);
    }
}
