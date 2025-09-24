<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        // Preferencia: URL de media (Spatie) si existe, si no, el campo og_image_url
        $cover = method_exists($this->resource, 'getFirstMediaUrl')
            ? ($this->getFirstMediaUrl('og', 'preview') ?: $this->og_image_url)
            : $this->og_image_url;

        return [
            'id'           => $this->id,
            'slug'         => $this->slug,
            'title'        => $this->title,
            'excerpt'      => $this->excerpt,
            'body'         => $this->body_md,
            'cover'        => $cover,
            'published_at' => optional($this->published_at)->toIso8601String(),

            'series' => $this->whenLoaded('series', fn () => [
                'title' => $this->series?->title,
                'slug'  => $this->series?->slug,
            ]),
            'tags'   => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => [
                'name' => $t->name,
                'slug' => $t->slug,
            ])),
        ];
    }
}
