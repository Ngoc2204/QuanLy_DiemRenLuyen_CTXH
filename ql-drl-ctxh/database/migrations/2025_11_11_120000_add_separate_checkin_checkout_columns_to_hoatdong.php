<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Thêm các cột riêng cho thời gian hết hạn check-in và check-out
     */
    public function up(): void
    {
        foreach (['hoatdongdrl', 'hoatdongctxh'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Thêm cột cho thời gian hết hạn của check-in
                if (!Schema::hasColumn($table->getTable(), 'CheckInExpiresAt')) {
                    $table->dateTime('CheckInExpiresAt')->nullable()->after('CheckInOpenAt');
                }
                // Thêm cột cho thời gian hết hạn của check-out
                if (!Schema::hasColumn($table->getTable(), 'CheckOutExpiresAt')) {
                    $table->dateTime('CheckOutExpiresAt')->nullable()->after('CheckOutOpenAt');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['hoatdongdrl', 'hoatdongctxh'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'CheckInExpiresAt')) {
                    $table->dropColumn('CheckInExpiresAt');
                }
                if (Schema::hasColumn($table->getTable(), 'CheckOutExpiresAt')) {
                    $table->dropColumn('CheckOutExpiresAt');
                }
            });
        }
    }
};
