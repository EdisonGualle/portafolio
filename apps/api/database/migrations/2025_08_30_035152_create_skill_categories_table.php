<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Ajustar tabla skills: eliminar columna category y agregar FK
        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'category')) {
                $table->dropColumn('category');
            }
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('skill_categories')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->string('category', 120)->nullable();
        });

        Schema::dropIfExists('skill_categories');
    }
};
