<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Post::query()->where('status', 'published');

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%$search%")
                  ->orWhere('excerpt', 'ilike', "%$search%");
            });
        }

        if ($tag = $request->string('tag')->toString()) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        if ($series = $request->string('series')->toString()) {
            $query->whereHas('series', fn ($q) => $q->where('slug', $series));
        }

        $query->orderByDesc('published_at');

        $perPage = min(max((int) $request->query('per_page', 10), 1), 50);
        $posts = $query->with(['series', 'tags'])->paginate($perPage);

        $posts->getCollection()->transform(fn ($p) => new PostResource($p));

        return $this->paginatedResponse($posts, 'Artículos obtenidos correctamente');
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published') {
            return $this->errorResponse('Artículo no publicado', 404);
        }

        $post->load(['series', 'tags']);

        return $this->successResponse(
            new PostResource($post),
            'Artículo obtenido correctamente'
        );
    }
}
