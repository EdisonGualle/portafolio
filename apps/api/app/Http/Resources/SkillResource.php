<?php

namespace App\Http\Resources;

class SkillResource extends SkillSummaryResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        if (isset($this->published_projects_count)) {
            $data['projects_count'] = (int) $this->published_projects_count;
        }

        if ($this->relationLoaded('projects')) {
            $data['projects'] = $this->projects
                ->map(fn ($project) => (new ProjectSummaryResource($project))->toArray($request))
                ->all();
        }

        return $data;
    }
}
