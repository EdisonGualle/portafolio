<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectSummaryResource;
use App\Models\Project;
use App\Traits\ApiResponse;
use App\Traits\HandlesQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    use ApiResponse;
    use HandlesQueryFilters;

    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'sort' => ['nullable', 'string', 'max:50'],
        ]);

        $perPage = $this->resolvePerPage($request, 12, 50);
        $searchTerm = $request->query('search');
        $statuses = $this->resolveStatuses($request, ['draft', 'published'], ['published']);
        $tagFilters = $this->parseListInput($request, 'tag', 'tags');
        $skillFilters = $this->parseListInput($request, 'skill', 'skills');
        $include = $this->requestedIncludes($request, ['skills', 'tags']);
        $featured = $this->booleanFilter($request, 'featured');
        [$sortColumn, $sortDirection] = $this->resolveSort($request->query('sort'), [
            'published_at' => 'projects.published_at',
            'created_at' => 'projects.created_at',
            'title' => 'projects.title',
            'sort_order' => 'projects.sort_order',
            'featured' => 'projects.featured',
        ], ['projects.published_at', 'desc', 'published_at']);

        $query = Project::query();

        $relations = [];
        if (in_array('tags', $include, true)) {
            $relations[] = 'tags:id,name,slug';
        }
        if (in_array('skills', $include, true)) {
            $relations[] = 'skills:id,name,level,sort_order,category_id';
            $relations[] = 'skills.category:id,name,sort_order';
        }

        if (! empty($relations)) {
            $query->with($relations);
        }

        if ($statuses === ['published']) {
            $query->published();
        } else {
            $query->where(function (Builder $builder) use ($statuses) {
                $first = true;

                if (in_array('draft', $statuses, true)) {
                    $builder->where('status', 'draft');
                    $first = false;
                }

                if (in_array('published', $statuses, true)) {
                    $builder->{ $first ? 'where' : 'orWhere' }(fn (Builder $published) => $published->published());
                }
            });
        }

        if (! empty($tagFilters)) {
            $tagIds = [];
            $tagSlugs = [];
            foreach ($tagFilters as $tag) {
                if (is_numeric($tag)) {
                    $tagIds[] = (int) $tag;
                } else {
                    $tagSlugs[] = $tag;
                }
            }

            $query->whereHas('tags', function (Builder $tagQuery) use ($tagIds, $tagSlugs) {
                $tagQuery->where(function (Builder $builder) use ($tagIds, $tagSlugs) {
                    $hasCondition = false;

                    if (! empty($tagIds)) {
                        $builder->whereIn('tags.id', $tagIds);
                        $hasCondition = true;
                    }

                    if (! empty($tagSlugs)) {
                        $builder->{ $hasCondition ? 'orWhereIn' : 'whereIn' }('tags.slug', $tagSlugs);
                    }
                });
            });
        }

        if (! empty($skillFilters)) {
            $skillIds = [];
            $skillNames = [];
            foreach ($skillFilters as $skill) {
                if (is_numeric($skill)) {
                    $skillIds[] = (int) $skill;
                } else {
                    $skillNames[] = $skill;
                }
            }

            $query->whereHas('skills', function (Builder $skillQuery) use ($skillIds, $skillNames) {
                $skillQuery->where(function (Builder $builder) use ($skillIds, $skillNames) {
                    $hasCondition = false;

                    if (! empty($skillIds)) {
                        $builder->whereIn('skills.id', $skillIds);
                        $hasCondition = true;
                    }

                    if (! empty($skillNames)) {
                        $builder->{ $hasCondition ? 'orWhereIn' : 'whereIn' }('skills.name', $skillNames);
                    }
                });
            });
        }

        if ($featured !== null) {
            $query->where('featured', $featured);
        }

        if ($searchTerm) {
            $query->where(function (Builder $builder) use ($searchTerm) {
                $builder
                    ->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('summary', 'like', "%{$searchTerm}%")
                    ->orWhere('body_md', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortColumn === 'projects.published_at' && $sortDirection === 'desc') {
            $query->orderByDesc('projects.featured')
                ->orderBy('projects.sort_order');
        }

        $query->orderBy($sortColumn, $sortDirection)
            ->orderByDesc('projects.created_at');

        $paginator = $query
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Project $project) => (new ProjectSummaryResource($project))->toArray($request));

        return $this->paginatedResponse($paginator, 'Proyectos obtenidos correctamente');
    }

    public function show(Request $request, Project $project)
    {
        $statuses = $this->resolveStatuses($request, ['draft', 'published'], ['published']);
        $allowPublished = in_array('published', $statuses, true);
        $allowDraft = in_array('draft', $statuses, true);

        $isPublished = $project->status === 'published' && $project->published_at?->lte(now());
        $isDraft = $project->status === 'draft';

        if ((! $isPublished || ! $allowPublished) && (! $isDraft || ! $allowDraft)) {
            return $this->errorResponse('Proyecto no disponible', Response::HTTP_NOT_FOUND);
        }

        $project->load([
            'tags:id,name,slug',
            'skills:id,name,level,sort_order,category_id',
            'skills.category:id,name,sort_order',
            'blocks' => fn ($query) => $query->orderBy('order_index'),
        ]);

        return $this->successResponse(
            (new ProjectResource($project))->toArray($request),
            'Proyecto obtenido correctamente'
        );
    }
}
