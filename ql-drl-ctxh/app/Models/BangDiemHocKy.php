<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BangDiemHocKy extends Model
{
    /**
     * Tên bảng trong cơ sở dữ liệu.
     */
    protected $table = 'bangdiemhocky';

    /**
     * Khóa chính của bảng.
     */
    protected $primaryKey = 'MaBangDiem';

    /**
     * Cho biết khóa chính có phải là số tự tăng hay không.
     * Dựa theo file SQL, MaBangDiem là AUTO_INCREMENT.
     */
    public $incrementing = true;

    /**
     * Cho biết model có nên tự động quản lý timestamps (created_at, updated_at) hay không.
     * Bảng của bạn không có 2 cột này.
     */
    public $timestamps = false;

    /**
     * Các thuộc tính có thể được gán hàng loạt (mass-assignable).
     */
    protected $fillable = [
        'MSSV',
        'MaHocKy',
        'MaMonHoc',
        'TenMonHoc',
        'DiemQT',
        'DiemThi',
        'DiemTongKet',
        'XepLoai',
    ];

    /**
     * Lấy thông tin sinh viên của bảng điểm này.
     * Quan hệ: bangdiemhocky.MSSV -> sinhvien.MSSV
     */
    public function sinhvien()
    {
        // Giả sử bạn có Model SinhVien
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    /**
     * Lấy thông tin học kỳ của bảng điểm này.
     * Quan hệ: bangdiemhocky.MaHocKy -> hocky.MaHocKy
     */
    public function hocky()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }

    // Lưu ý: Bảng của bạn không có ràng buộc khóa ngoại cho MaMonHoc
    // Nếu bạn có bảng 'monhoc', bạn có thể thêm quan hệ tương tự:
    // public function monhoc()
    // {
    //     return $this->belongsTo(MonHoc::class, 'MaMonHoc', 'MaMonHoc');
    // }
}
