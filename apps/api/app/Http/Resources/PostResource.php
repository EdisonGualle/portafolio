<?php

namespace App\Http\Resources;

class PostResource extends PostSummaryResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $data['body'] = $this->body_md;
        $data['og_image_url'] = $this->og_image_url;

        return $data;
    }
}
