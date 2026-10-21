<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('position');
        });

        // Generate slugs for existing experiences
        $experiences = DB::table('experiences')->select('id', 'position', 'organization_name')->get();
        $usedSlugs = [];

        foreach ($experiences as $exp) {
            $baseSlug = Str::slug(trim(($exp->position ?? 'pengalaman').' '.($exp->organization_name ?? '')));
            if (empty($baseSlug)) {
                $baseSlug = 'pengalaman-'.$exp->id;
            }

            $slug = $baseSlug;
            $counter = 1;
            while (in_array($slug, $usedSlugs, true) || DB::table('experiences')->where('slug', $slug)->where('id', '!=', $exp->id)->exists()) {
                $counter++;
                $slug = "{$baseSlug}-{$counter}";
            }

            $usedSlugs[] = $slug;
            DB::table('experiences')->where('id', $exp->id)->update(['slug' => $slug]);
        }

        Schema::table('experiences', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
