<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiemCTXH extends Model
{
    protected $table = 'diemctxh';
    
    // SỬA 1: Khóa chính là MSSV
    protected $primaryKey = 'MSSV'; 
    
    // Khóa chính không phải là số tự tăng
    public $incrementing = false; 
    
    // Bảng này không dùng timestamps (created_at/updated_at)
    public $timestamps = false; 

    // SỬA 2: Các cột được phép gán
    // (Bảng của bạn không có MaCTXH và XepLoai)
    protected $fillable = [
        'MSSV', 
        'TongDiem', 
        'NgayCapNhat' // Thêm NgayCapNhat (quan trọng cho logic 'firstOrCreate')
    ];

    public function sinhvien()
    {
        return $this->belongsTo(SinhVien::class, 'MSSV', 'MSSV');
    }
}
