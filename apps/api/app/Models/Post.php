<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body_md',
        'status',
        'published_at',
        'series_id',
        'og_image_url',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function previewTokens(): MorphMany
    {
        return $this->morphMany(PreviewToken::class, 'previewable', 'model_type', 'model_id');
    }

    public function activePreviewToken(): ?PreviewToken
    {
        return $this->previewTokens()->valid()->latest('expires_at')->first();
    }
}
