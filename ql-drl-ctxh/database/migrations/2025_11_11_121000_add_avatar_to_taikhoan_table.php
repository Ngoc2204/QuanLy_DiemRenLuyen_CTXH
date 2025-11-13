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
        if (!Schema::hasTable('taikhoan')) {
            return;
        }

        Schema::table('taikhoan', function (Blueprint $table) {
            if (!Schema::hasColumn('taikhoan', 'Avatar')) {
                $table->string('Avatar', 255)->nullable()->after('VaiTro');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('taikhoan')) {
            return;
        }

        Schema::table('taikhoan', function (Blueprint $table) {
            if (Schema::hasColumn('taikhoan', 'Avatar')) {
                $table->dropColumn('Avatar');
            }
        });
    }
};
