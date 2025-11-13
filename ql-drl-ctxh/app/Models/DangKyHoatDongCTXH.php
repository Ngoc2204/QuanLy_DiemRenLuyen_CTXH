<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKyHoatDongCTXH extends Model
{
    protected $table = 'dangkyhoatdongctxh';
    protected $primaryKey = 'MaDangKy';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    protected $casts = [
        'NgayDangKy' => 'datetime',
    ];

    protected $fillable = [
        'MSSV',
        'MaHoatDong',
        'NgayDangKy',
        'TrangThaiDangKy',
        'CheckInAt',
        'CheckOutAt',
        'TrangThaiThamGia',
        'thanh_toan_id',
    ];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }

    public function hoatdong()
    {
        return $this->belongsTo(HoatDongCTXH::class, 'MaHoatDong', 'MaHoatDong');
    }
    public function thanhToan()
    {
        return $this->belongsTo(ThanhToan::class, 'thanh_toan_id', 'id');
    }

    public function scopeKiemTraTrungDiaDiem($query, $mssv, $dotId, $diaDiemId)
    {
        return $query->where('MSSV', $mssv)
            // Chỉ kiểm tra các đơn đã "giữ chỗ" (đã duyệt hoặc đang chờ thanh toán)
            ->whereIn('TrangThaiDangKy', ['DaDuyet', 'ChoThanhToan', 'Đã duyệt'])
            // Dùng whereHas để join với bảng hoatdongctxh
            ->whereHas('hoatdong', function ($q) use ($dotId, $diaDiemId) {
                $q->where('dot_id', $dotId)
                    ->where('diadiem_id', $diaDiemId);
            })
            ->exists(); // Trả về true nếu tìm thấy, false nếu không
    }
}
