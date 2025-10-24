<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoatDongCTXH extends Model
{
    protected $table = 'hoatdongctxh';
    protected $primaryKey = 'MaHoatDong';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaHoatDong', 'TenHoatDong', 'MoTa', 
        'ThoiGianBatDau', 'ThoiGianKetThuc', 'ThoiHanHuy', 'DiaDiem', 'SoLuong', 'LoaiHoatDong', 'MaQuyDinhDiem'
    ];

    protected $casts = [
        'ThoiGianBatDau' => 'datetime',
        'ThoiGianKetThuc' => 'datetime',
        'ThoiHanHuy' => 'datetime',
    ];


    public function quydinh()
    {
        return $this->belongsTo(QuyDinhDiemCTXH::class, 'MaQuyDinhDiem', 'MaDiem');
    }

    public function dangky()
    {
        return $this->hasMany(DangKyHoatDongCTXH::class, 'MaHoatDong', 'MaHoatDong');
    }

    public function sinhVienDangKy() // <- Sửa lại tên hàm này (chữ 's' viết thường)
    {
        return $this->belongsToMany(SinhVien::class, 'dangkyhoatdongctxh', 'MaHoatDong', 'MSSV')
                    ->withPivot('NgayDangKy', 'TrangThaiDangKy'); // Lấy thêm cột từ bảng trung gian nếu cần
    }
}
