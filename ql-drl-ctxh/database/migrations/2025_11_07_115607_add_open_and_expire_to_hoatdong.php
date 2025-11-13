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
        // Áp dụng cho cả hai bảng hoatdongdrl và hoatdongctxh
        foreach (['hoatdongdrl', 'hoatdongctxh'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'CheckInOpenAt')) {
                    $table->dateTime('CheckInOpenAt')->nullable()->after('CheckInToken');
                }
                if (!Schema::hasColumn($table->getTable(), 'CheckOutOpenAt')) {
                    $table->dateTime('CheckOutOpenAt')->nullable()->after('CheckOutToken');
                }
                if (!Schema::hasColumn($table->getTable(), 'TokenExpiresAt')) {
                    $table->dateTime('TokenExpiresAt')->nullable()->after('CheckOutOpenAt');
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['hoatdongdrl', 'hoatdongctxh'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'CheckInOpenAt')) {
                    $table->dropColumn('CheckInOpenAt');
                }
                if (Schema::hasColumn($table->getTable(), 'CheckOutOpenAt')) {
                    $table->dropColumn('CheckOutOpenAt');
                }
                if (Schema::hasColumn($table->getTable(), 'TokenExpiresAt')) {
                    $table->dropColumn('TokenExpiresAt');
                }
            });
        }
    }
};
