<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeriesResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => (int) $this->sort_order,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];

        if (isset($this->published_posts_count)) {
            $data['posts_count'] = (int) $this->published_posts_count;
        }

        if ($this->relationLoaded('posts')) {
            $data['posts'] = $this->posts
                ->map(fn ($post) => (new PostSummaryResource($post))->toArray($request))
                ->all();
        }

        return $data;
    }
}
