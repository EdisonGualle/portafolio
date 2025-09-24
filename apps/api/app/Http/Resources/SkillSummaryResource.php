<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkillSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => (int) $this->level,
            'sort_order' => (int) $this->sort_order,
            'category' => $this->when($this->relationLoaded('category') && $this->category, function () use ($request) {
                return (new SkillCategoryResource($this->category))->toArray($request);
            }),
        ];
    }
}
