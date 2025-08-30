<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'body_md',
        'repo_url',
        'demo_url',
        'featured',
        'sort_order',
        'status',
        'published_at',
        'og_image_url',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'project_skill');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'project_tag');
    }

    /** Bloques dinámicos (se implementa más adelante) */
    // public function blocks(): HasMany
    // {
    //     return $this->hasMany(ProjectBlock::class);
    // }
}
