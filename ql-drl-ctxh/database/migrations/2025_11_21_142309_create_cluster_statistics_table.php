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
        if (!Schema::hasTable('cluster_statistics')) {
            Schema::create('cluster_statistics', function (Blueprint $table) {
                $table->id('ClusterStatID');
                $table->integer('ClusterID');
                $table->integer('TotalStudents')->default(0);
                $table->decimal('AvgParticipationRate', 5, 2)->nullable();
                $table->decimal('AvgScore', 5, 2)->nullable();
                $table->json('TopInterests')->nullable();
                $table->json('TopActivities')->nullable();
                $table->timestamp('CreatedAt')->useCurrent();
                
                $table->unique('ClusterID');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cluster_statistics');
    }
};
