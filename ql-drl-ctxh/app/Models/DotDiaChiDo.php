<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DotDiaChiDo extends Model
{
    use HasFactory;

    /**
     * Tên bảng mà model này quản lý.
     * The table associated with the model.
     */
    protected $table = 'dot_dia_chi_do';

    /**
     * Các trường có thể được gán hàng loạt.
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'TenDot',
        'NgayBatDau',
        'NgayKetThuc',
        'TrangThai',
    ];

    /**
     * Lấy các suất hoạt động (hoatdongctxh) thuộc về đợt này.
     * Get the activities (slots) that belong to this campaign.
     */
    public function hoatDongs()
    {
        return $this->hasMany(HoatDongCTXH::class, 'dot_id', 'id');
    }
}

