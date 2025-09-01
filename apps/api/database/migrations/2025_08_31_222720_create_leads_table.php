<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160);
            $table->string('email', 180);
            $table->string('subject', 200)->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'read', 'replied'])->default('new');
            $table->string('source', 120)->nullable();
            $table->json('utm_json')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->timestamp('processed_at')->nullable();
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
