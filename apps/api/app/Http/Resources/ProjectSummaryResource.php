<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'featured' => (bool) $this->featured,
            'sort_order' => (int) $this->sort_order,
            'status' => $this->status,
            'repo_url' => $this->repo_url,
            'demo_url' => $this->demo_url,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'tags' => $this->when($this->relationLoaded('tags'), function () use ($request) {
                return $this->tags->map(fn ($tag) => (new TagResource($tag))->toArray($request))->all();
            }),
            'skills' => $this->when($this->relationLoaded('skills'), function () use ($request) {
                return $this->skills->map(fn ($skill) => (new SkillSummaryResource($skill))->toArray($request))->all();
            }),
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
