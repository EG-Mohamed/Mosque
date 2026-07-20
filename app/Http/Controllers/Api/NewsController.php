<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NewsIndexRequest;
use App\Http\Requests\Api\PaginatedApiRequest;
use App\Http\Resources\NewsCategoryResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NewsController extends Controller
{
    public function index(NewsIndexRequest $request): AnonymousResourceCollection
    {
        $locale = $this->locale($request);
        $search = trim((string) $request->string('search'));

        $query = News::query()
            ->with('categories')
            ->published();

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($locale, $search): void {
                $builder->where("title->{$locale}", 'like', "%{$search}%")
                    ->orWhere("excerpt->{$locale}", 'like', "%{$search}%")
                    ->orWhere("content->{$locale}", 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', fn (Builder $builder): Builder => $builder->whereKey($request->integer('category_id')));
        }

        if ($request->filled('published_from')) {
            $query->whereDate('published_at', '>=', $request->string('published_from'));
        }

        if ($request->filled('published_to')) {
            $query->whereDate('published_at', '<=', $request->string('published_to'));
        }

        return NewsResource::collection($query->paginate($request->perPage(12)));
    }

    public function show(string $slug): NewsResource
    {
        $news = News::query()
            ->with('categories')
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return new NewsResource($news);
    }

    public function categories(PaginatedApiRequest $request): AnonymousResourceCollection
    {
        $categories = NewsCategory::query()
            ->active()
            ->paginate($request->perPage(50));

        return NewsCategoryResource::collection($categories);
    }

    private function locale(PaginatedApiRequest $request): string
    {
        $locale = trim((string) ($request->query('locale') ?? $request->query('lang') ?? app()->getLocale()));

        return $locale !== '' ? $locale : app()->getLocale();
    }
}
