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
        if (!Schema::hasColumn('hoatdongdrl', 'category_tags')) {
            Schema::table('hoatdongdrl', function (Blueprint $table) {
                $table->string('category_tags')->nullable()->comment('Comma-separated interest IDs');
            });
        }

        if (!Schema::hasColumn('hoatdongctxh', 'category_tags')) {
            Schema::table('hoatdongctxh', function (Blueprint $table) {
                $table->string('category_tags')->nullable()->comment('Comma-separated interest IDs');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoatdongdrl', function (Blueprint $table) {
            $table->dropColumn('category_tags');
        });

        Schema::table('hoatdongctxh', function (Blueprint $table) {
            $table->dropColumn('category_tags');
        });
    }
};
