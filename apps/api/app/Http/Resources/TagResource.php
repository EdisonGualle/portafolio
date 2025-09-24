<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];

        if (isset($this->published_posts_count)) {
            $data['posts_count'] = (int) $this->published_posts_count;
        }

        if (isset($this->published_projects_count)) {
            $data['projects_count'] = (int) $this->published_projects_count;
        }

        if ($this->relationLoaded('posts')) {
            $data['posts'] = $this->posts
                ->map(fn ($post) => (new PostSummaryResource($post))->toArray($request))
                ->all();
        }

        if ($this->relationLoaded('projects')) {
            $data['projects'] = $this->projects
                ->map(fn ($project) => (new ProjectSummaryResource($project))->toArray($request))
                ->all();
        }

        return $data;
    }
}
