<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DieuChinhDRL extends Model
{
    use HasFactory;

    protected $table = 'dieuchinhdrl';
    protected $primaryKey = 'MaDieuChinh';

    protected $fillable = [
        'MSSV',
        'MaHocKy',
        'MaNV',
        'MaDiem', // <-- Lưu khóa ngoại đến bảng quy định
        'NgayCapNhat',
    ];

    /**
     * Lấy thông tin sinh viên
     */
    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    /**
     * Lấy thông tin học kỳ
     */
    public function hocky()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }

    /**
     * Lấy thông tin nhân viên đã cập nhật
     */
    public function nhanvien()
    {
        return $this->belongsTo(NhanVien::class, 'MaNV', 'MaNV');
    }

    /**
     * Lấy thông tin quy định đã áp dụng
     */
    public function quydinh()
    {
        // Liên kết đến Model QuyDinhDiemRL
        return $this->belongsTo(QuyDinhDiemRL::class, 'MaDiem', 'MaDiem');
    }
}