<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaDiemDiaChiDo extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     */
    protected $table = 'diadiemdiachido';

    /**
     * Các trường có thể được gán hàng loạt.
     */
    protected $fillable = [
        'TenDiaDiem',
        'DiaChi',
        'GiaTien',
        'TrangThai',
    ];

    /**
     * Mối quan hệ: Một địa điểm có thể có nhiều suất hoạt động (hoatdongctxh)
     */
    public function hoatDongs()
    {
        // 'diadiem_id' là khóa ngoại trong bảng 'hoatdongctxh'
        return $this->hasMany(HoatDongCTXH::class, 'diadiem_id', 'id');
    }
}