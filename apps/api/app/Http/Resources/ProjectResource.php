<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'slug'        => $this->slug,
            'title'       => $this->title,
            'excerpt'     => $this->summary,
            'body'        => $this->body_md,
            'cover'       => $this->og_image_url,
            'repo_url'    => $this->repo_url,
            'demo_url'    => $this->demo_url,
            'featured'    => (bool) $this->featured,
            'published_at'=> optional($this->published_at)->toIso8601String(),

            // Relaciones resumidas (opcional)
            'tags'   => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => [
                'name' => $t->name,
                'slug' => $t->slug,
            ])),
            'skills' => $this->whenLoaded('skills', fn () => $this->skills->map(fn ($s) => [
                'name' => $s->name,
                'level'=> $s->level,
            ])),
        ];
    }
}
