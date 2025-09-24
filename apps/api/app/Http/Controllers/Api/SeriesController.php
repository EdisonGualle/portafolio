<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeriesResource;
use App\Models\Series;
use App\Traits\ApiResponse;
use App\Traits\HandlesQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    use ApiResponse;
    use HandlesQueryFilters;

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'max:50'],
        ]);

        $perPage = $this->resolvePerPage($request, 15, 100);
        $searchTerm = $request->query('search');
        $includeCounts = $this->booleanFilter($request, 'include_counts') ?? false;
        [$sortColumn, $sortDirection] = $this->resolveSort($request->query('sort'), [
            'title' => 'series.title',
            'created_at' => 'series.created_at',
            'sort_order' => 'series.sort_order',
        ], ['series.sort_order', 'asc', 'sort_order']);

        $query = Series::query();

        if ($includeCounts) {
            $query->withCount([
                'posts as published_posts_count' => fn ($q) => $q->published(),
            ]);
        }

        if ($searchTerm) {
            $query->where(function (Builder $builder) use ($searchTerm) {
                $builder
                    ->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $query->orderBy($sortColumn, $sortDirection)
            ->orderBy('series.title');

        $paginator = $query
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Series $series) => (new SeriesResource($series))->toArray($request));

        return $this->paginatedResponse($paginator, 'Series obtenidas correctamente');
    }

    public function show(Request $request, Series $series)
    {
        $include = $this->requestedIncludes($request, ['posts']);

        if (in_array('posts', $include, true)) {
            $series->load(['posts' => function ($query) {
                $query->published()
                    ->with(['tags:id,name,slug', 'series:id,title,slug'])
                    ->orderByDesc('published_at');
            }]);
        }

        $series->loadCount([
            'posts as published_posts_count' => fn ($q) => $q->published(),
        ]);

        return $this->successResponse(
            (new SeriesResource($series))->toArray($request),
            'Serie obtenida correctamente'
        );
    }
}
