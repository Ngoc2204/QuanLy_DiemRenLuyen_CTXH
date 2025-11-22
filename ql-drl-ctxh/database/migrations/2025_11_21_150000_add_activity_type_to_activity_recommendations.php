<?php

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
        if (Schema::hasTable('activity_recommendations')) {
            Schema::table('activity_recommendations', function (Blueprint $table) {
                if (!Schema::hasColumn('activity_recommendations', 'activity_type')) {
                    $table->string('activity_type', 20)->default('drl')->after('MaHoatDong');
                }
                if (!Schema::hasColumn('activity_recommendations', 'recommendation_score')) {
                    $table->decimal('recommendation_score', 5, 2)->nullable()->after('activity_type');
                }
                if (!Schema::hasColumn('activity_recommendations', 'recommendation_reason')) {
                    $table->string('recommendation_reason', 500)->nullable()->after('recommendation_score');
                }
                if (!Schema::hasColumn('activity_recommendations', 'viewed_at')) {
                    $table->timestamp('viewed_at')->nullable()->after('recommendation_reason');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('activity_recommendations')) {
            Schema::table('activity_recommendations', function (Blueprint $table) {
                if (Schema::hasColumn('activity_recommendations', 'activity_type')) {
                    $table->dropColumn('activity_type');
                }
                if (Schema::hasColumn('activity_recommendations', 'recommendation_score')) {
                    $table->dropColumn('recommendation_score');
                }
                if (Schema::hasColumn('activity_recommendations', 'recommendation_reason')) {
                    $table->dropColumn('recommendation_reason');
                }
                if (Schema::hasColumn('activity_recommendations', 'viewed_at')) {
                    $table->dropColumn('viewed_at');
                }
            });
        }
    }
};
