<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

// Spatie Media Library
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

// spatie/image v3 (enums)
use Spatie\Image\Enums\Fit;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body_md',
        'status',
        'published_at',
        'series_id',
        // ⚠️ deprecado: lo migraremos a Media Library y luego podremos eliminarlo
        'og_image_url',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /* -------------------------
       Relaciones
       ------------------------- */
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

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where(function ($scope) {
                $scope
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /* -------------------------
       Media Library
       ------------------------- */
    public function registerMediaCollections(): void
    {
        // Imagen destacada para OG (singleFile reemplaza la anterior)
        $this->addMediaCollection('og')
            ->singleFile()
            ->useFallbackUrl(asset('images/og-default.png'))
            ->useFallbackPath(public_path('images/og-default.png'));

        // Galería opcional (múltiples)
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $isLocal = app()->isLocal();

        // Imagen OG (1200x630) → ideal para compartir en redes
        $og = $this->addMediaConversion('og')
            ->fit(Fit::Crop, 1200, 630)
            ->format('webp');

        // Miniatura para listados/tablas
        $thumb = $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 400, 225)
            ->format('webp');

        // Previsualización mediana (ej. en cards)
        $preview = $this->addMediaConversion('preview')
            ->fit(Fit::Crop, 800, 450)
            ->format('webp');

        // Cola condicional → instantáneo en local, en background en prod
        $isLocal ? $og->nonQueued()     : $og->queued();
        $isLocal ? $thumb->nonQueued()  : $thumb->queued();
        $isLocal ? $preview->nonQueued() : $preview->queued();
    }
}
