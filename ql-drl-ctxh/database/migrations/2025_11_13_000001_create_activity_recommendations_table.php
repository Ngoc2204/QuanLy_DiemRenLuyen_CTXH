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
        Schema::create('activity_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('MSSV'); // Mã sinh viên
            $table->string('MaHoatDong'); // Mã hoạt động
            $table->string('activity_type')->nullable(); // 'drl' or 'ctxh'
            $table->decimal('recommendation_score', 8, 4)->default(0); // Điểm đề xuất (0-100)
            $table->string('recommendation_reason')->nullable(); // Lý do đề xuất
            $table->integer('priority')->default(0); // Độ ưu tiên
            $table->timestamp('recommended_at')->useCurrent();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
            $table->index(['MSSV', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_recommendations');
    }
};
