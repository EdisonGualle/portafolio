<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';

    protected $fillable = [
        'name',
        'role',
        'bio_md',
        'photo_url',
        'email',
        'phone',
        'location',
        'socials_json',
    ];

    protected $casts = [
        'socials_json' => 'array',
    ];
}
