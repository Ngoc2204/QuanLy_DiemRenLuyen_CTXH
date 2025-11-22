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
        if (!Schema::hasTable('activity_topic_tags')) {
            Schema::create('activity_topic_tags', function (Blueprint $table) {
                $table->id('ActivityTagID');
                $table->string('MaHoatDong', 255);//For both DRL and CTXH
                $table->unsignedBigInteger('InterestID');
                $table->decimal('RelevanceScore', 3, 2)->comment('0-1, relevance level');
                
                $table->foreign('MaHoatDong')->references('MaHoatDong')->on('hoatdongdrl')->onDelete('cascade');
                $table->foreign('InterestID')->references('InterestID')->on('interests')->onDelete('cascade');
                $table->unique(['MaHoatDong', 'InterestID']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_topic_tags');
    }
};
