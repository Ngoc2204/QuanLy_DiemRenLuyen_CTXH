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
        Schema::create('thongbao', function (Blueprint $table) {
            $table->id('MaThongBao'); // Khóa chính tự tăng
            $table->string('TieuDe'); // Tiêu đề thông báo
            $table->text('NoiDung'); // Nội dung chi tiết
            $table->string('MaNguoiTao'); // Mã nhân viên tạo (FK) - Cần đảm bảo khớp với kiểu dữ liệu MaNV
            $table->enum('LoaiThongBao', ['Chung', 'DRL', 'CTXH'])->default('Chung'); // Phân loại
            $table->timestamps(); // Tự động tạo created_at và updated_at

            // Khóa ngoại (Tùy chọn nhưng nên có)
            // $table->foreign('MaNguoiTao')->references('MaNV')->on('nhanvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thongbao');
    }
};

