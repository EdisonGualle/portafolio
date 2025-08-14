<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name'      => $this->name,
            'role'      => $this->role,
            'bio'       => $this->bio_md,
            'photo_url' => $this->photo_url,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'location'  => $this->location,
            'socials'   => $this->socials_json,
        ];
    }
}
