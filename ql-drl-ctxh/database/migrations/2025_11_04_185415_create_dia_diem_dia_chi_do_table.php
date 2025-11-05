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
        // Bảng này để Admin quản lý các địa điểm và giá tiền cố định
        Schema::create('diadiemdiachido', function (Blueprint $table) {
            $table->id(); // Mã địa điểm (tự tăng)
            $table->string('TenDiaDiem'); // VD: Dinh Độc Lập
            $table->text('DiaChi')->nullable(); // VD: 135 Nam Kỳ Khởi Nghĩa, Bến Nghé, Q.1
            $table->decimal('GiaTien', 10, 0)->default(0); // Giá tiền mặc định
            $table->enum('TrangThai', ['KhaDung', 'TamNgung'])->default('KhaDung');
            $table->timestamps();

            // Đặt charset và collation giống CSDL của bạn để tránh lỗi khóa ngoại
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diadiemdiachido');
    }
};