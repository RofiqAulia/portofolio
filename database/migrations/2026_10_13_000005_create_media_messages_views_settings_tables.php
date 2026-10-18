<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->morphs('mediable');
            $table->string('type')->default('image'); // image, video
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['mediable_type', 'mediable_id', 'sort_order']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->string('status')->default('new'); // new, read, replied, archived
            $table->string('ip_address')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->char('visitor_hash', 64);
            $table->text('referrer')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('created_at');
            $table->index(['project_id', 'created_at']);
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('media');
    }
};
