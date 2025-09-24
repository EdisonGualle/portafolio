<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'series' => $this->when($this->relationLoaded('series') && $this->series, function () use ($request) {
                return [
                    'id' => $this->series->id,
                    'title' => $this->series->title,
                    'slug' => $this->series->slug,
                    'description' => $this->series->description,
                ];
            }),
            'tags' => $this->when($this->relationLoaded('tags'), function () use ($request) {
                return $this->tags->map(fn ($tag) => (new TagResource($tag))->toArray($request))->all();
            }),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
