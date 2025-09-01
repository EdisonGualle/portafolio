<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class PreviewToken extends Model
{
    use HasFactory;

    protected $table = 'preview_tokens';

    protected $fillable = [
        'token',
        'model_type',
        'model_id',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Autogenera token UUID y expiración por defecto si no vienen seteados.
     */
    protected static function booted(): void
    {
        static::creating(function (self $token): void {
            if (blank($token->token)) {
                $token->token = (string) Str::uuid();
            }

            if (blank($token->expires_at)) {
                $token->expires_at = now()->addHours(48);
            }
        });
    }

    /**
     * Modelo al que pertenece el token (post, project, etc.).
     */
    public function previewable(): MorphTo
    {
        // columns: model_type, model_id
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    /**
     * Usuario que generó el token (opcional).
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** ======== Scopes & helpers ======== */

    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expires_at);
    }

    /**
     * Crea un token para un modelo dado.
     */
    public static function createFor(Model $model, int $ttlHours = 48, ?int $userId = null): self
    {
        return static::create([
            'model_type' => $model::class,
            'model_id'   => $model->getKey(),
            'expires_at' => now()->addHours($ttlHours),
            'created_by' => $userId,
        ]);
    }

    /**
     * Renueva la expiración del token.
     */
    public function renew(int $ttlHours = 48): self
    {
        $this->expires_at = now()->addHours($ttlHours);
        $this->save();

        return $this;
    }

    /**
     * (Opcional) Si defines una ruta nombrada para previews, puedes exponer el URL:
     */
    // public function url(): string
    // {
    //     return route('preview.show', ['token' => $this->token]);
    // }
}
