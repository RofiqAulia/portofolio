<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Sertifikasi Profesi')->index();
            $table->string('issuer')->nullable();
            $table->string('issued_year')->nullable();
            $table->string('status_label')->nullable()->default('Terverifikasi');
            $table->string('credential_id')->nullable();
            $table->string('credential_url')->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_label')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->json('files')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
