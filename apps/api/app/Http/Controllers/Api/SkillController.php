<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use App\Traits\ApiResponse;
use App\Traits\HandlesQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    use ApiResponse;
    use HandlesQueryFilters;

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:120'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort' => ['nullable', 'string', 'max:50'],
        ]);

        $perPage = $this->resolvePerPage($request, 50, 100);
        $searchTerm = $request->query('search');
        $categoryFilters = $this->parseListInput($request, 'category', 'categories');
        $include = $this->requestedIncludes($request, ['category']);
        $includeCounts = $this->booleanFilter($request, 'include_counts') ?? false;
        [$sortColumn, $sortDirection] = $this->resolveSort($request->query('sort'), [
            'name' => 'skills.name',
            'level' => 'skills.level',
            'sort_order' => 'skills.sort_order',
            'created_at' => 'skills.created_at',
        ], ['skills.sort_order', 'asc', 'sort_order']);

        $query = Skill::query();

        if (in_array('category', $include, true)) {
            $query->with('category:id,name,sort_order');
        }

        if ($includeCounts) {
            $query->withCount([
                'projects as published_projects_count' => fn ($q) => $q->published(),
            ]);
        }

        if (! empty($categoryFilters)) {
            $query->where(function (Builder $builder) use ($categoryFilters) {
                $ids = [];
                $names = [];
                $withNull = false;

                foreach ($categoryFilters as $filter) {
                    if (is_numeric($filter)) {
                        $ids[] = (int) $filter;
                        continue;
                    }

                    $normalized = strtolower($filter);
                    if (in_array($normalized, ['none', 'null'], true)) {
                        $withNull = true;
                        continue;
                    }

                    $names[] = $filter;
                }

                $first = true;

                if (! empty($ids)) {
                    $builder->whereIn('category_id', $ids);
                    $first = false;
                }

                if (! empty($names)) {
                    $builder->{ $first ? 'whereHas' : 'orWhereHas' }('category', function (Builder $categoryQuery) use ($names) {
                        $categoryQuery->whereIn('name', $names);
                    });
                    $first = false;
                }

                if ($withNull) {
                    $builder->{ $first ? 'whereNull' : 'orWhereNull' }('category_id');
                }
            });
        }

        if ($searchTerm) {
            $query->where('name', 'like', "%{$searchTerm}%");
        }

        $query->orderBy($sortColumn, $sortDirection)
            ->orderBy('skills.name');

        $paginator = $query
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Skill $skill) => (new SkillResource($skill))->toArray($request));

        return $this->paginatedResponse($paginator, 'Habilidades obtenidas correctamente');
    }

    public function show(Request $request, Skill $skill)
    {
        $include = $this->requestedIncludes($request, ['category', 'projects']);
        $skill->loadMissing('category:id,name,sort_order');

        if (in_array('projects', $include, true)) {
            $skill->load(['projects' => function ($query) {
                $query->published()
                    ->with(['tags:id,name,slug', 'skills:id,name'])
                    ->orderByDesc('published_at');
            }]);
        }

        $skill->loadCount([
            'projects as published_projects_count' => fn ($q) => $q->published(),
        ]);

        return $this->successResponse(
            (new SkillResource($skill))->toArray($request),
            'Habilidad obtenida correctamente'
        );
    }
}
