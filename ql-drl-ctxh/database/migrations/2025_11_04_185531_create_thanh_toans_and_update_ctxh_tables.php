<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 1️⃣ Tạo bảng THANHTOANS (Logic mới)
        if (!Schema::hasTable('thanhtoans')) {
            Schema::create('thanhtoans', function (Blueprint $table) {
                $table->id();
                // SỬA: Đổi sang unsignedInteger để khớp với MaDangKy (AUTO_INCREMENT)
                $table->integer('MaDangKy');


                $table->decimal('SoTien', 10, 0);
                $table->enum('TrangThai', ['ChuaThanhToan', 'DaThanhToan', 'ThatBai', 'DaHuy'])
                      ->default('ChuaThanhToan');
                $table->string('MaGiaoDich')->nullable();
                $table->string('PhuongThuc')->nullable();
                $table->timestamps();

                $table->foreign('MaDangKy', 'fk_thanhtoans_madangky')
                      ->references('MaDangKy')
                      ->on('dangkyhoatdongctxh') 
                      ->onDelete('cascade');

                $table->charset = 'utf8mb4';
                $table->collation = 'utf8mb4_unicode_ci';
            });
        }

        // 2️⃣ Cập nhật bảng HOATDONGCTXH
        if (Schema::hasTable('hoatdongctxh')) {
            Schema::table('hoatdongctxh', function (Blueprint $table) {
                
                // SỬA: Đã XÓA logic thêm 'GiaTien' để khớp với Model

                if (!Schema::hasColumn('hoatdongctxh', 'dot_id')) {
                    $table->unsignedBigInteger('dot_id')->nullable()->after('MaHoatDong');
                    $table->foreign('dot_id')->references('id')->on('dot_dia_chi_do')->onDelete('set null');
                }
                if (!Schema::hasColumn('hoatdongctxh', 'diadiem_id')) {
                    $table->unsignedBigInteger('diadiem_id')->nullable()->after('dot_id');
                    $table->foreign('diadiem_id')->references('id')->on('diadiemdiachido')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        // 1️⃣ Xóa cột thêm trong HOATDONGCTXH
        if (Schema::hasTable('hoatdongctxh')) {
            Schema::table('hoatdongctxh', function (Blueprint $table) {

                // SỬA: Thêm try-catch để rollback an toàn
                if (Schema::hasColumn('hoatdongctxh', 'diadiem_id')) {
                    try { $table->dropForeign(['diadiem_id']); } 
                    catch (\Exception $e) { /* ignore */ }
                    $table->dropColumn('diadiem_id');
                }

                if (Schema::hasColumn('hoatdongctxh', 'dot_id')) {
                    try { $table->dropForeign(['dot_id']); } 
                    catch (\Exception $e) { /* ignore */ }
                    $table->dropColumn('dot_id');
                }
                
                 if (Schema::hasColumn('hoatdongctxh', 'GiaTien')) {
                    $table->dropColumn('GiaTien');
                }
            });
        }

        // 2️⃣ Xóa bảng THANHTOANS
        Schema::dropIfExists('thanhtoans');

        Schema::enableForeignKeyConstraints();
    }
};

