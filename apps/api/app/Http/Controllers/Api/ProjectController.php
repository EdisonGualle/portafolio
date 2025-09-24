<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $query = Project::query()->where('status', 'published');

        // Filtros
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%$search%")
                  ->orWhere('summary', 'ilike', "%$search%");
            });
        }

        if ($request->boolean('featured')) {
            $query->where('featured', true);
        }

        if ($tag = $request->string('tag')->toString()) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        if ($skill = $request->string('skill')->toString()) {
            $query->whereHas('skills', fn ($q) => $q->where('name', $skill));
        }

        $query->orderByDesc('featured')
              ->orderBy('sort_order')
              ->orderByDesc('published_at');

        $perPage = min(max((int) $request->query('per_page', 12), 1), 50);
        $projects = $query->with(['tags', 'skills'])->paginate($perPage);

        $projects->getCollection()->transform(fn ($p) => new ProjectResource($p));

        return $this->paginatedResponse($projects, 'Proyectos obtenidos correctamente');
    }

    public function show(Project $project)
    {
        if ($project->status !== 'published') {
            return $this->errorResponse('Proyecto no publicado', 404);
        }

        $project->load(['tags', 'skills', 'blocks' => fn ($q) => $q->orderBy('order_index')]);

        $data = (new ProjectResource($project))->resolve();
        $data['blocks'] = $project->blocks->map(fn ($b) => [
            'type'  => $b->type,
            'data'  => $b->data_json,
            'order' => $b->order_index,
        ])->all();

        return $this->successResponse($data, 'Proyecto obtenido correctamente');
    }
}
