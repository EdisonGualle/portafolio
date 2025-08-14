<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:150',
            'role'         => 'nullable|string|max:150',
            'bio_md'       => 'nullable|string',
            'photo_url'    => 'nullable|url|max:255',
            'email'        => 'nullable|email|max:150',
            'phone'        => 'nullable|string|max:50',
            'location'     => 'nullable|string|max:150',
            'socials_json' => 'nullable|array',
        ];
    }
}
