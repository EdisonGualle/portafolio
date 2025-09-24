<?php

namespace App\Http\Resources;

class ProjectResource extends ProjectSummaryResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $data['body'] = $this->body_md;
        $data['og_image_url'] = $this->og_image_url;
        $data['blocks'] = $this->when($this->relationLoaded('blocks'), function () use ($request) {
            return $this->blocks
                ->sortBy('order_index')
                ->values()
                ->map(fn ($block) => (new ProjectBlockResource($block))->toArray($request))
                ->all();
        });

        return $data;
    }
}
