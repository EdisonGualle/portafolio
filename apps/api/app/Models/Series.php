<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Series extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'sort_order',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
