<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuyDinhDiemRLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $data = [
            ['MaDiem' => 'DRL01', 'TenCongViec' => 'Điểm mặc định (Tiêu chí 1)', 'DiemNhan' => 20],
            ['MaDiem' => 'DRL02', 'TenCongViec' => 'Thi rớt 1 môn', 'DiemNhan' => -1],
            ['MaDiem' => 'DRL03', 'TenCongViec' => 'Thi rớt 2 môn', 'DiemNhan' => -3],
            ['MaDiem' => 'DRL04', 'TenCongViec' => 'Thi rớt trên 5 môn', 'DiemNhan' => -15],
            ['MaDiem' => 'DRL05', 'TenCongViec' => 'Vi phạm quy chế học vụ (Quyết định cảnh báo học vụ)', 'DiemNhan' => -20],
            ['MaDiem' => 'DRL06', 'TenCongViec' => 'Bị cấm thi', 'DiemNhan' => -10],
            ['MaDiem' => 'DRL07', 'TenCongViec' => 'Tham gia NCKH đạt giải Cấp Quốc gia', 'DiemNhan' => 30],
            ['MaDiem' => 'DRL08', 'TenCongViec' => 'Tham gia NCKH đạt giải Cấp Thành phố', 'DiemNhan' => 20],
            ['MaDiem' => 'DRL09', 'TenCongViec' => 'Tham gia NCKH đạt giải Cấp trường', 'DiemNhan' => 10],
            ['MaDiem' => 'DRL10', 'TenCongViec' => 'Tham gia NCKH đạt giải Cấp khoa', 'DiemNhan' => 5],
            ['MaDiem' => 'DRL11', 'TenCongViec' => 'Tham gia NCKH đạt giải Đơn vị ngoài trường', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL12', 'TenCongViec' => 'Tham gia hội thảo, chuyên đề NCKH Cấp Trường', 'DiemNhan' => 5],
            ['MaDiem' => 'DRL13', 'TenCongViec' => 'Tham gia hội thảo, chuyên đề NCKH Cấp Khoa', 'DiemNhan' => 3],
            ['MaDiem' => 'DRL14', 'TenCongViec' => 'Đăng ký hội thảo, chuyên đề NCKH mà không tham gia', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL15', 'TenCongViec' => 'Sinh viên được khen thưởng đột xuất (có xác nhận)', 'DiemNhan' => 10],
            ['MaDiem' => 'DRL16', 'TenCongViec' => 'Sinh viên mồ côi cả cha lẫn mẹ', 'DiemNhan' => 10],
            ['MaDiem' => 'DRL17', 'TenCongViec' => 'Sinh viên khuyết tật, khó khăn trong đi lại và sinh hoạt', 'DiemNhan' => 10],
            ['MaDiem' => 'DRL18', 'TenCongViec' => 'Điểm mặc định (Tiêu chí 2)', 'DiemNhan' => 25],
            ['MaDiem' => 'DRL19', 'TenCongViec' => 'Không tham gia SHCD', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL20', 'TenCongViec' => 'Không tham gia BHYT', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL21', 'TenCongViec' => 'Không tham gia khám sức khỏe theo thông báo', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL22', 'TenCongViec' => 'Trang phục không nghiêm túc', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL23', 'TenCongViec' => 'Đóng học phí trễ hạn', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL24', 'TenCongViec' => 'Không giữ vệ sinh trường, lớp', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL25', 'TenCongViec' => 'Không đeo thẻ SV khi đến trường', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL26', 'TenCongViec' => 'Hút thuốc không đúng nơi quy định', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL27', 'TenCongViec' => 'Không cập nhật thông tin sinh viên/ thông tin ngoại trú', 'DiemNhan' => -10],
            ['MaDiem' => 'DRL28', 'TenCongViec' => 'Không tham gia các khảo sát', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL29', 'TenCongViec' => 'Không hoàn thiện hồ sơ sinh viên theo quy định', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL30', 'TenCongViec' => 'Vi phạm kỷ luật khiển trách', 'DiemNhan' => -25],
            ['MaDiem' => 'DRL31', 'TenCongViec' => 'Không thực hiện đúng quy định của nhà trường', 'DiemNhan' => -20],
            ['MaDiem' => 'DRL32', 'TenCongViec' => 'Vi phạm nội quy, quy chế khác', 'DiemNhan' => -10],
            ['MaDiem' => 'DRL33', 'TenCongViec' => 'Tham dự hoạt động tuyên truyền, giáo dục chính trị, tư tưởng; phổ biến pháp luật', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL34', 'TenCongViec' => 'Tham dự hoạt động, hội thi quảng bá hình ảnh, thương hiệu Nhà trường', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL35', 'TenCongViec' => 'Tham gia sự kiện chính trị xã hội (hội nghị, đại hội, lễ kỷ niệm...) do Nhà trường tổ chức', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL36', 'TenCongViec' => 'Tham gia hưởng ứng hoạt động chính trị - xã hội, phòng chống tội phạm... do Nhà trường điều động', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL37', 'TenCongViec' => 'Tham gia VHVN, TDTT Cấp trường', 'DiemNhan' => 6],
            ['MaDiem' => 'DRL38', 'TenCongViec' => 'Tham gia VHVN, TDTT Cấp khoa', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL39', 'TenCongViec' => 'Tham gia VHVN, TDTT Cấp CLB/lớp', 'DiemNhan' => 2],
            ['MaDiem' => 'DRL40', 'TenCongViec' => 'Hoạt động VHVN, TDTT tại địa phương (có xác nhận)', 'DiemNhan' => 2],
            ['MaDiem' => 'DRL41', 'TenCongViec' => 'Tham gia VHVN, TDTT và đạt giải', 'DiemNhan' => 4],
            ['MaDiem' => 'DRL42', 'TenCongViec' => 'Thành viên sinh hoạt thường xuyên CLB Sinh viên (trừ CLB học thuật, thiện nguyện)', 'DiemNhan' => 3],
            ['MaDiem' => 'DRL43', 'TenCongViec' => 'Đăng ký hoạt động (TC3) nhưng không tham dự', 'DiemNhan' => -5],
            ['MaDiem' => 'DRL44', 'TenCongViec' => 'Điểm mặc định (Tiêu chí 4)', 'DiemNhan' => 25],
            ['MaDiem' => 'DRL45', 'TenCongViec' => 'Tham gia hoạt động tình nguyện Cấp trường', 'DiemNhan' => 5],
            ['MaDiem' => 'DRL46', 'TenCongViec' => 'Tham gia hoạt động tình nguyện Cấp Khoa', 'DiemNhan' => 3],
            ['MaDiem' => 'DRL47', 'TenCongViec' => 'Hoạt động tình nguyện tại địa phương hoặc ngoài trường (có xác nhận)', 'DiemNhan' => 3],
            ['MaDiem' => 'DRL48', 'TenCongViec' => 'Phát ngôn không chuẩn mực trên mạng xã hội', 'DiemNhan' => -25],
            ['MaDiem' => 'DRL49', 'TenCongViec' => 'Gây rối, làm mất an ninh chính trị xã hội', 'DiemNhan' => -25],
            ['MaDiem' => 'DRL50', 'TenCongViec' => 'Vi phạm quy định địa phương nơi cư trú', 'DiemNhan' => -15],
            ['MaDiem' => 'DRL51', 'TenCongViec' => 'Vi phạm luật an toàn giao thông', 'DiemNhan' => -15],
            ['MaDiem' => 'DRL52', 'TenCongViec' => 'BCH Đoàn/Hội cấp trường, Chi ủy viên chi bộ đảng SV', 'DiemNhan' => 10],
            ['MaDiem' => 'DRL53', 'TenCongViec' => 'BCH Đoàn/Hội cấp khoa; Chủ nhiệm CLB cấp Trường; Lớp trưởng', 'DiemNhan' => 8],
            ['MaDiem' => 'DRL54', 'TenCongViec' => 'BCN CLB cấp Trường; Lớp phó kiêm bí thư', 'DiemNhan' => 7],
            ['MaDiem' => 'DRL55', 'TenCongViec' => 'Thành viên CLB/đội/nhóm cấp Trường', 'DiemNhan' => 3],
            ['MaDiem' => 'DRL56', 'TenCongViec' => 'Cán bộ lớp, đoàn, hội không thực hiện nhiệm vụ, không họp', 'DiemNhan' => -10],
        ];

        // Truncate table trước khi insert
        DB::table('quydinhdiemrl')->truncate();

        // Insert data
        foreach ($data as $item) {
            DB::table('quydinhdiemrl')->insert($item);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✓ Đã import ' . count($data) . ' quy định điểm DRL thành công!');
    }
}
