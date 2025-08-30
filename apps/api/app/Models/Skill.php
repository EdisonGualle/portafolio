<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'level',
        'sort_order',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }

    // Relación con proyectos
    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class, 'project_skill');
    // }
}
