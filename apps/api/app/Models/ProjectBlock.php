<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectBlock extends Model
{
    protected $fillable = [
        'project_id',
        'type',
        'data_json',
        'order_index',
    ];

    protected $casts = [
        'data_json' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
