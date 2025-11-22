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
        // Fix the relevance_score column to allow values > 9.9999
        Schema::table('validation_reports', function (Blueprint $table) {
            $table->decimal('relevance_score', 8, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('validation_reports', function (Blueprint $table) {
            $table->decimal('relevance_score', 5, 4)->change();
        });
    }
};
