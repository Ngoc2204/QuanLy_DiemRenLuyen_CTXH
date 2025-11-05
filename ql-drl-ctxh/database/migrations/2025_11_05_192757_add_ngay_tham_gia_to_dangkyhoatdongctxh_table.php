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
        Schema::table('dangkyhoatdongctxh', function (Blueprint $table) {
            // Thêm cột này để lưu ngày SV chọn, cho phép NULL vì HĐ khác không cần
            $table->date('NgayThamGia')->nullable()->after('TrangThaiDangKy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dangkyhoatdongctxh', function (Blueprint $table) {
            //
        });
    }
};
