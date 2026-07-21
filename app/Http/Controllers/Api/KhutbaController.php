<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\KhutbaIndexRequest;
use App\Http\Requests\Api\PaginatedApiRequest;
use App\Http\Resources\KhutbaCategoryResource;
use App\Http\Resources\KhutbaResource;
use App\Models\Khutba;
use App\Models\KhutbaCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KhutbaController extends Controller
{
    public function index(KhutbaIndexRequest $request): AnonymousResourceCollection
    {
        $locale = app()->getLocale();
        $search = trim((string) $request->string('search'));

        $query = Khutba::query()
            ->with('categories')
            ->published();

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($locale, $search): void {
                $builder->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("speaker->{$locale}", 'like', "%{$search}%")
                    ->orWhere("topic->{$locale}", 'like', "%{$search}%")
                    ->orWhere("summary->{$locale}", 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn (Builder $builder): Builder => $builder->whereKey($request->integer('category_id')));
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->string('date'));
        }

        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->string('to'));
        }

        return KhutbaResource::collection($query->paginate($request->perPage(15)));
    }

    public function show(string $slug): KhutbaResource
    {
        $khutba = Khutba::query()
            ->with('categories')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return new KhutbaResource($khutba);
    }

    public function categories(PaginatedApiRequest $request): AnonymousResourceCollection
    {
        $categories = KhutbaCategory::query()
            ->active()
            ->paginate($request->perPage(50));

        return KhutbaCategoryResource::collection($categories);
    }
}
