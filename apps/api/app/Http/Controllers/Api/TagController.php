<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponse;
use App\Traits\HandlesQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TagController extends Controller
{
    use ApiResponse;
    use HandlesQueryFilters;

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:180'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'string', 'max:20'],
        ]);

        $perPage = $this->resolvePerPage($request, 30, 100);
        $searchTerm = $request->query('search');
        $type = strtolower((string) $request->query('type', ''));
        $includeCounts = $this->booleanFilter($request, 'include_counts') ?? false;
        [$sortColumn, $sortDirection] = $this->resolveSort($request->query('sort'), [
            'name' => 'tags.name',
            'created_at' => 'tags.created_at',
            'updated_at' => 'tags.updated_at',
        ], ['tags.name', 'asc', 'name']);

        $query = Tag::query();

        if ($includeCounts) {
            $query->withCount([
                'posts as published_posts_count' => fn ($q) => $q->published(),
                'projects as published_projects_count' => fn ($q) => $q->published(),
            ]);
        }

        if ($type === 'posts') {
            $query->whereHas('posts', fn (Builder $q) => $q->published());
        } elseif ($type === 'projects') {
            $query->whereHas('projects', fn (Builder $q) => $q->published());
        }

        if ($searchTerm) {
            $query->where(function (Builder $builder) use ($searchTerm) {
                $builder
                    ->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('slug', 'like', "%{$searchTerm}%");
            });
        }

        $query->orderBy($sortColumn, $sortDirection)
            ->orderBy('tags.slug');

        $paginator = $query
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Tag $tag) => (new TagResource($tag))->toArray($request));

        return $this->paginatedResponse($paginator, 'Etiquetas obtenidas correctamente');
    }

    public function show(Request $request, Tag $tag)
    {
        $include = $this->requestedIncludes($request, ['posts', 'projects']);

        $tag->loadCount([
            'posts as published_posts_count' => fn ($q) => $q->published(),
            'projects as published_projects_count' => fn ($q) => $q->published(),
        ]);

        if (in_array('posts', $include, true)) {
            $tag->load(['posts' => function ($query) {
                $query->published()
                    ->with(['tags:id,name,slug', 'series:id,title,slug'])
                    ->orderByDesc('published_at');
            }]);
        }

        if (in_array('projects', $include, true)) {
            $tag->load(['projects' => function ($query) {
                $query->published()
                    ->with([
                        'tags:id,name,slug',
                        'skills:id,name,level,sort_order,category_id',
                        'skills.category:id,name,sort_order',
                    ])
                    ->orderByDesc('published_at')
                    ->orderBy('sort_order');
            }]);
        }

        return $this->successResponse(
            (new TagResource($tag))->toArray($request),
            'Etiqueta obtenida correctamente'
        );
    }
}
