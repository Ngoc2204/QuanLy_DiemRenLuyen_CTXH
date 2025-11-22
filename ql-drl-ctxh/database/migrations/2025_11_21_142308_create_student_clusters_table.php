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
        if (!Schema::hasTable('student_clusters')) {
            Schema::create('student_clusters', function (Blueprint $table) {
                $table->id('ClusterAssignmentID');
                $table->string('MSSV', 20);
                $table->integer('ClusterID');
                $table->string('ClusterName', 255)->nullable();
                $table->timestamp('AssignmentDate')->useCurrent();
                
                $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
                $table->index('ClusterID');
                $table->index('MSSV');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_clusters');
    }
};
