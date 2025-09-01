<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Solo Postgres: habilita pgcrypto para gen_random_uuid()
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');

        Schema::create('preview_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Token único (UUID) generado por Postgres
            $table->uuid('token')
                ->unique()
                ->default(DB::raw('gen_random_uuid()'));

            // Relación polimórfica con cualquier modelo (posts, projects, etc.)
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');

            // Expiración obligatoria
            $table->timestampTz('expires_at');

            // Quién generó el token (opcional)
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestampsTz();

            // Índices útiles
            $table->index(['model_type', 'model_id']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preview_tokens');
    }
};
