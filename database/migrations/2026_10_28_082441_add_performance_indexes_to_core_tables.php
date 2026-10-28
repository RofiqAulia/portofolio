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
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->index(['status', 'sort_order'], 'projects_status_sort_order_idx');
            });
        }

        if (Schema::hasTable('experiences')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->index(['type', 'status', 'sort_order'], 'experiences_type_status_sort_idx');
                $table->index(['status', 'sort_order', 'start_date'], 'experiences_status_sort_date_idx');
            });
        }

        if (Schema::hasTable('documentations')) {
            Schema::table('documentations', function (Blueprint $table) {
                $table->index(['is_published', 'sort_order'], 'docs_is_published_sort_order_idx');
            });
        }

        if (Schema::hasTable('certifications')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->index(['is_published', 'sort_order'], 'certs_is_published_sort_order_idx');
            });
        }

        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->index('created_at', 'messages_created_at_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('projects')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropIndex('projects_status_sort_order_idx');
            });
        }

        if (Schema::hasTable('experiences')) {
            Schema::table('experiences', function (Blueprint $table) {
                $table->dropIndex('experiences_type_status_sort_idx');
                $table->dropIndex('experiences_status_sort_date_idx');
            });
        }

        if (Schema::hasTable('documentations')) {
            Schema::table('documentations', function (Blueprint $table) {
                $table->dropIndex('docs_is_published_sort_order_idx');
            });
        }

        if (Schema::hasTable('certifications')) {
            Schema::table('certifications', function (Blueprint $table) {
                $table->dropIndex('certs_is_published_sort_order_idx');
            });
        }

        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropIndex('messages_created_at_idx');
            });
        }
    }
};
