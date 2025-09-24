<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostSummaryResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use App\Traits\HandlesQueryFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
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
        $seriesFilters = $this->parseListInput($request, 'series');
        [$sortColumn, $sortDirection] = $this->resolveSort($request->query('sort'), [
            'published_at' => 'posts.published_at',
            'created_at' => 'posts.created_at',
            'title' => 'posts.title',
        ], ['posts.published_at', 'desc', 'published_at']);

        $query = Post::query()
            ->with([
                'tags:id,name,slug',
                'series:id,title,slug,description',
            ]);

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

        if (! empty($seriesFilters)) {
            $seriesIds = [];
            $seriesSlugs = [];

            foreach ($seriesFilters as $series) {
                if (is_numeric($series)) {
                    $seriesIds[] = (int) $series;
                } else {
                    $seriesSlugs[] = $series;
                }
            }

            $query->whereHas('series', function (Builder $seriesQuery) use ($seriesIds, $seriesSlugs) {
                $seriesQuery->where(function (Builder $builder) use ($seriesIds, $seriesSlugs) {
                    $hasCondition = false;

                    if (! empty($seriesIds)) {
                        $builder->whereIn('series.id', $seriesIds);
                        $hasCondition = true;
                    }

                    if (! empty($seriesSlugs)) {
                        $builder->{ $hasCondition ? 'orWhereIn' : 'whereIn' }('series.slug', $seriesSlugs);
                    }
                });
            });
        }

        if ($searchTerm) {
            $query->where(function (Builder $builder) use ($searchTerm) {
                $builder
                    ->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                    ->orWhere('body_md', 'like', "%{$searchTerm}%");
            });
        }

        $query->orderBy($sortColumn, $sortDirection)
            ->orderByDesc('posts.created_at');

        $paginator = $query
            ->paginate($perPage)
            ->withQueryString()
            ->through(fn (Post $post) => (new PostSummaryResource($post))->toArray($request));

        return $this->paginatedResponse($paginator, 'Publicaciones obtenidas correctamente');
    }

    public function show(Request $request, Post $post)
    {
        $statuses = $this->resolveStatuses($request, ['draft', 'published'], ['published']);
        $allowPublished = in_array('published', $statuses, true);
        $allowDraft = in_array('draft', $statuses, true);

        $isPublished = $post->status === 'published' && $post->published_at?->lte(now());
        $isDraft = $post->status === 'draft';

        if ((! $isPublished || ! $allowPublished) && (! $isDraft || ! $allowDraft)) {
            return $this->errorResponse('Entrada no disponible', Response::HTTP_NOT_FOUND);
        }

        $post->load([
            'tags:id,name,slug',
            'series:id,title,slug,description',
        ]);

        return $this->successResponse(
            (new PostResource($post))->toArray($request),
            'Publicación obtenida correctamente'
        );
    }
}
