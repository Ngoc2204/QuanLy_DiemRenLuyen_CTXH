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
        // Bảng này để quản lý các đợt (chiến dịch)
        Schema::create('dot_dia_chi_do', function (Blueprint $table) {
            $table->id(); // Mã đợt (tự tăng)
            $table->string('TenDot'); // VD: "Chiến dịch Địa chỉ đỏ T11/2025"
            $table->date('NgayBatDau');
            $table->date('NgayKetThuc');
            $table->enum('TrangThai', ['SapDienRa', 'DangDienRa', 'DaKetThuc'])
                  ->default('SapDienRa');
            $table->timestamps();
            
            // Đặt charset và collation
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dot_dia_chi_do');
    }
};