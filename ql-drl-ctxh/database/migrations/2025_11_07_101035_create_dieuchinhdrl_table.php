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
        Schema::create('dieuchinhdrl', function (Blueprint $table) {
            $table->id('MaDieuChinh');
            $table->string('MSSV', 50);
            $table->string('MaHocKy', 255);
            $table->string('MaNV', 50); // Nhân viên nào đã thêm
            
            // SỬA: Thay vì lưu text, ta lưu Mã Quy Định
            $table->string('MaDiem', 10); // Vd: 'DRL05'

            $table->dateTime('NgayCapNhat');
            $table->timestamps();

            // Tạo khóa ngoại
            $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
            $table->foreign('MaHocKy')->references('MaHocKy')->on('hocky')->onDelete('cascade');
            $table->foreign('MaNV')->references('MaNV')->on('nhanvien')->onDelete('cascade');
            
            // SỬA: Thêm khóa ngoại trỏ đến bảng Quy Định
            $table->foreign('MaDiem')->references('MaDiem')->on('quydinhdiemrl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dieuchinhdrl');
    }
};
