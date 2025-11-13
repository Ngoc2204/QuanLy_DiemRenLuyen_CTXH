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
            // Thêm cột 'thanh_toan_id'
            $table->unsignedBigInteger('thanh_toan_id')->nullable()->after('TrangThaiThamGia');

            // Tạo khóa ngoại
            $table->foreign('thanh_toan_id')
                ->references('id')
                ->on('thanh_toan')
                ->onDelete('SET NULL'); // Nếu hóa đơn bị xóa, giữ lại đơn đăng ký
        });
    }

    public function down(): void
    {
        Schema::table('dangkyhoatdongctxh', function (Blueprint $table) {
            $table->dropForeign(['thanh_toan_id']);
            $table->dropColumn('thanh_toan_id');
        });
    }
};
