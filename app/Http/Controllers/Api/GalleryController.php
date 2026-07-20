<?php

namespace App\Http\Controllers\Api;

use App\Enums\MediaType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GalleryIndexRequest;
use App\Http\Resources\MediaItemResource;
use App\Models\MediaItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GalleryController extends Controller
{
    public function index(GalleryIndexRequest $request): AnonymousResourceCollection
    {
        $query = MediaItem::query()->orderBy('sort_order')->orderBy('id');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        } else {
            $query->where('type', MediaType::Image);
        }

        if ($request->filled('collection')) {
            $query->where('collection', $request->string('collection'));
        }

        return MediaItemResource::collection($query->paginate($request->perPage(24)));
    }
}
