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
        // Bảng lưu kết quả validation
        Schema::create('validation_reports', function (Blueprint $table) {
            $table->id();
            $table->timestamp('validation_date')->useCurrent();
            
            // Internal Validation Scores
            $table->decimal('silhouette_score', 5, 4)->nullable();
            $table->decimal('davies_bouldin_index', 5, 4)->nullable();
            $table->decimal('calinski_harabasz_index', 8, 2)->nullable();
            $table->decimal('cluster_balance', 5, 4)->nullable();
            $table->decimal('internal_quality_score', 5, 4)->nullable();
            
            // External Validation Scores
            $table->decimal('interest_cohesion', 5, 4)->nullable();
            $table->decimal('activity_behavior_cohesion', 5, 4)->nullable();
            $table->decimal('performance_cohesion', 5, 4)->nullable();
            $table->decimal('external_relevance_score', 5, 4)->nullable();
            
            // Stability Validation Scores
            $table->decimal('adjusted_rand_index', 5, 4)->nullable();
            $table->decimal('consistency_rate', 5, 4)->nullable();
            $table->decimal('stability_score', 5, 4)->nullable();
            
            // Recommendation Quality Scores
            $table->decimal('coverage', 5, 4)->nullable();
            $table->decimal('relevance_score', 5, 4)->nullable();
            $table->decimal('click_through_rate', 5, 4)->nullable();
            $table->decimal('recommendation_quality_score', 5, 4)->nullable();
            
            // Overall Score
            $table->decimal('overall_score', 5, 4)->nullable();
            $table->string('interpretation')->nullable();
            
            // JSON data for detailed metrics
            $table->json('business_metrics')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });

        // Bảng lưu lịch sử clustering
        Schema::create('clustering_history', function (Blueprint $table) {
            $table->id();
            $table->timestamp('clustering_date')->useCurrent();
            $table->integer('num_clusters');
            $table->integer('num_iterations');
            $table->decimal('final_inertia', 12, 4)->nullable();
            $table->integer('num_students');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Bảng lưu feature vector snapshots (để debug)
        Schema::create('feature_vector_logs', function (Blueprint $table) {
            $table->id();
            $table->string('MSSV', 20);
            $table->json('feature_vector');
            $table->integer('cluster_id')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->foreign('MSSV')->references('MSSV')->on('sinhvien');
            $table->timestamps();
        });

        // Bảng lưu recommendation logs (để track user behavior)
        Schema::create('recommendation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('MSSV', 20);
            $table->string('MaHoatDong', 50);
            $table->string('activity_type', 10); // 'drl' or 'ctxh'
            $table->decimal('recommendation_score', 5, 2);
            $table->boolean('viewed')->default(false);
            $table->boolean('attended')->default(false);
            $table->timestamp('recommended_at')->useCurrent();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->foreign('MSSV')->references('MSSV')->on('sinhvien');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_logs');
        Schema::dropIfExists('feature_vector_logs');
        Schema::dropIfExists('clustering_history');
        Schema::dropIfExists('validation_reports');
    }
};
