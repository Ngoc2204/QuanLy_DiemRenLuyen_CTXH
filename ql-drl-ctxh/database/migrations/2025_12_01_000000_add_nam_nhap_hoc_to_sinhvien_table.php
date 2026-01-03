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
        Schema::table('sinhvien', function (Blueprint $table) {
            // Thêm trường NamNhapHoc (năm nhập học)
            // VD: 2022, 2023, 2024
            // Nullable: để tương thích với dữ liệu cũ, sẽ tính auto nếu null
            $table->integer('NamNhapHoc')->nullable()->after('MaLop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sinhvien', function (Blueprint $table) {
            $table->dropColumn('NamNhapHoc');
        });
    }
};
