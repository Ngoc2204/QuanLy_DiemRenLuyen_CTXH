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
        Schema::create('thanh_toan', function (Blueprint $table) {
            $table->id();
            $table->string('MSSV', 10); // Sinh viên nào thanh toán
            $table->decimal('TongTien', 10, 0); // Số tiền
            $table->string('TrangThai')->default('Chờ thanh toán'); // Chờ thanh toán, Đã thanh toán, Đã hủy
            $table->string('PhuongThuc')->nullable(); // TienMat, Online
            $table->string('MaGiaoDich')->nullable(); // Mã NV nhập (tiền mặt) hoặc Mã Bank/Momo
            $table->timestamp('NgayThanhToan')->nullable(); // Ngày NV xác nhận
            $table->timestamps();

            $table->foreign('MSSV')->references('MSSV')->on('sinhvien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanh_toan');
    }
};
