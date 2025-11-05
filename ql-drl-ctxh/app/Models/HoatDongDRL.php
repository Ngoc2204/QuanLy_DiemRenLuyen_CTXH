<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoatDongDRL extends Model
{
    protected $table = 'hoatdongdrl';
    protected $primaryKey = 'MaHoatDong';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'MaHoatDong', 'TenHoatDong', 'MaGV', 'MoTa', 
        'ThoiGianBatDau', 'ThoiGianKetThuc', 'ThoiHanHuy', 'DiaDiem', 'SoLuong', 'LoaiHoatDong','MaHocKy', 'MaQuyDinhDiem'
    ];

    protected $casts = [
        'ThoiGianBatDau' => 'datetime',
        'ThoiGianKetThuc' => 'datetime',
        'ThoiHanHuy' => 'datetime',
    ];

    public function quydinh()
    {
        return $this->belongsTo(QuyDinhDiemRL::class, 'MaQuyDinhDiem', 'MaDiem');
    }

    public function dangky()
    {
        return $this->hasMany(DangKyHoatDongDRL::class, 'MaHoatDong', 'MaHoatDong');
    }

    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'MaHocKy', 'MaHocKy');
    }

    public function sinhVienDangKy()
    {
        return $this->belongsToMany(SinhVien::class, 'dangkyhoatdongdrl', 'MaHoatDong', 'MSSV')
                    ->withPivot('NgayDangKy', 'TrangThaiDangKy');
    }

    public function giangVienPhuTrach()
    {
        return $this->belongsTo(GiangVien::class, 'MaGV', 'MaGV');
    }
    public function phanBos()
    {
        return $this->hasMany(PhanBoSoLuong::class, 'MaHoatDong', 'MaHoatDong');
    }
}
