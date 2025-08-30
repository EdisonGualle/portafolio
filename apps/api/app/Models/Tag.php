<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    // Relación con proyectos
    // public function projects()
    // {
    //     return $this->belongsToMany(Project::class, 'project_tag');
    // }

    // Relación con posts
    // public function posts()
    // {
    //     return $this->belongsToMany(Post::class, 'post_tag');
    // }
}
