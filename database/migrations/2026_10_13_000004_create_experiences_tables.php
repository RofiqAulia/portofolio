<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // internship, organization
            $table->string('position');
            $table->string('organization_name');
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('summary', 300)->nullable();
            $table->longText('story')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('status')->default('draft'); // draft, published
            $table->unsignedInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['type', 'status', 'start_date']);
        });

        Schema::create('experience_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->string('content', 255);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('experience_technology', function (Blueprint $table) {
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained('technologies')->cascadeOnDelete();
            $table->primary(['experience_id', 'technology_id']);
        });

        Schema::create('experience_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained('experiences')->cascadeOnDelete();
            $table->string('type'); // file, link
            $table->string('title');
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_attachments');
        Schema::dropIfExists('experience_technology');
        Schema::dropIfExists('experience_highlights');
        Schema::dropIfExists('experiences');
    }
};
