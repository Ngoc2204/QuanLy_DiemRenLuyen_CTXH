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
        if (!Schema::hasTable('activity_recommendations')) {
            Schema::create('activity_recommendations', function (Blueprint $table) {
                $table->id('RecommendationID');
                $table->string('MSSV', 20);
                $table->string('MaHoatDong', 255);//For both DRL and CTXH
                $table->decimal('MatchScore', 5, 2)->nullable();
                $table->string('Reason', 500)->nullable();
                $table->timestamp('RecommendedAt')->useCurrent();
                $table->boolean('IsAccepted')->nullable()->comment('NULL: not viewed, TRUE: accepted, FALSE: rejected');
                
                $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
                $table->foreign('MaHoatDong')->references('MaHoatDong')->on('hoatdongdrl')->onDelete('cascade');
                $table->index('MSSV');
                $table->index('MaHoatDong');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_recommendations');
    }
};
