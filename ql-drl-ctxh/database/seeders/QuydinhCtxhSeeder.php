<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuydinhCtxhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Chỉ insert các record không tồn tại
        DB::table('quydinhdiemctxh')->insertOrIgnore([
            ['MaDiem' => 'CTXH01', 'TenCongViec' => 'Mùa hè xanh', 'DiemNhan' => 50],
            ['MaDiem' => 'CTXH02', 'TenCongViec' => 'Hiến máu nhân đạo', 'DiemNhan' => 25],
            ['MaDiem' => 'CTXH03', 'TenCongViec' => 'Tham gia các hoạt động từ thiện khác', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH04', 'TenCongViec' => 'Tham gia đội hình olympic: Toán, Vật lý, Tin học, Mác - Leenin, đội hình HSSV giỏi nghề, đội hình tham gia hoạt động cấp thành phố và cấp khối', 'DiemNhan' => 35],
            ['MaDiem' => 'CTXH05', 'TenCongViec' => 'Tham gia đội hình olympic: Toán, Vật lý, Tin học, Mác - Leenin, đội hình HSSV giỏi nghề, đội hình tham gia hoạt động cấp thành phố và cấp khối (Giải nhất)', 'DiemNhan' => 70],
            ['MaDiem' => 'CTXH06', 'TenCongViec' => 'Tham gia đội hình olympic: Toán, Vật lý, Tin học, Mác - Leenin, đội hình HSSV giỏi nghề, đội hình tham gia hoạt động cấp thành phố và cấp khối (Giải nhì)', 'DiemNhan' => 60],
            ['MaDiem' => 'CTXH07', 'TenCongViec' => 'Tham gia đội hình olympic: Toán, Vật lý, Tin học, Mác - Leenin, đội hình HSSV giỏi nghề, đội hình tham gia hoạt động cấp thành phố và cấp khối (Giải ba)', 'DiemNhan' => 50],
            ['MaDiem' => 'CTXH08', 'TenCongViec' => 'Tham gia đội hình olympic: Toán, Vật lý, Tin học, Mác - Leenin, đội hình HSSV giỏi nghề, đội hình tham gia hoạt động cấp thành phố và cấp khối (Khuyến khích)', 'DiemNhan' => 45],
            ['MaDiem' => 'CTXH09', 'TenCongViec' => 'Tham gia đội tuyển thể dục thể thao', 'DiemNhan' => 35],
            ['MaDiem' => 'CTXH10', 'TenCongViec' => 'Tham gia đội tuyển thể dục thể thao (Khuyến khích)', 'DiemNhan' => 45],
            ['MaDiem' => 'CTXH11', 'TenCongViec' => 'Tham gia đội tuyển thể dục thể thao (Giải nhất)', 'DiemNhan' => 70],
            ['MaDiem' => 'CTXH12', 'TenCongViec' => 'Tham gia đội tuyển thể dục thể thao (Giải nhì)', 'DiemNhan' => 60],
            ['MaDiem' => 'CTXH13', 'TenCongViec' => 'Tham gia đội tuyển thể dục thể thao (Giải ba)', 'DiemNhan' => 50],
            ['MaDiem' => 'CTXH14', 'TenCongViec' => 'Tham gia hoạt động văn hóa, văn nghệ', 'DiemNhan' => 20],
            ['MaDiem' => 'CTXH15', 'TenCongViec' => 'Tham gia các hoạt động khác', 'DiemNhan' => 30],
            ['MaDiem' => 'CTXH16', 'TenCongViec' => 'Tham gia trực KTX', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH17', 'TenCongViec' => 'Hỗ trợ ổn định trật tự khu vực lớp học', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH18', 'TenCongViec' => 'Hỗ trợ ổn định trật tự cho các hoạt động (có sự phê duyệt của BGH) theo yêu cầu của các phòng, khoa, trung tâm thuộc Trường', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH19', 'TenCongViec' => 'Tham gia, tư vấn, hướng dẫn thí sinh đăng ký nguyện vọng 2 vào Trường, thí sinh trúng tuyển làm hồ sơ nhập học', 'DiemNhan' => 40],
            ['MaDiem' => 'CTXH20', 'TenCongViec' => 'Tham gia hỗ trợ công tác phục vụ cho các lễ hội, ngày lễ của Nhà trường', 'DiemNhan' => 15],
            ['MaDiem' => 'CTXH21', 'TenCongViec' => 'Tiếp sức mùa thi', 'DiemNhan' => 50],
            ['MaDiem' => 'CTXH22', 'TenCongViec' => 'Tham gia công tác an ninh trật tự trong sân trường', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH23', 'TenCongViec' => 'Hỗ trợ về chuyên môn, nghiệp vụ theo yêu cầu của các phòng, đơn vị thuộc Trường', 'DiemNhan' => 10],
            ['MaDiem' => 'CTXH24', 'TenCongViec' => 'Hành trình về nguồn', 'DiemNhan' => 20],
            ['MaDiem' => 'CTXH25', 'TenCongViec' => 'Tham gia các hoạt động bảo vệ môi trường như "Chủ nhật xanh", "Lớp học không rác", "Sân trường sạch đẹp"…', 'DiemNhan' => 10],
        ]);
    }
}
