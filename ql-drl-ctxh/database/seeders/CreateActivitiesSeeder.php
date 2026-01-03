<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $hocKy = 'HK1_2526'; // Học kỳ 1 năm 2025-2026

        // Lấy danh sách giảng viên
        $giangViens = DB::table('giangvien')->pluck('MaGV')->toArray();
        $giangVien = $giangViens[0] ?? 'GV001';

        // Lấy quy định điểm
        $quyDinhDRL = DB::table('quydinhdiemrl')->pluck('MaDiem')->first() ?? '1';
        $quyDinhCTXH = 'CTXH26';

        // Lấy danh sách sinh viên
        $sinhViens = DB::table('sinhvien')->pluck('MSSV')->toArray();

        // =============================
        // 10 HOẠT ĐỘNG DRL
        // =============================

        // 5 hoạt động DRL trong quá khứ
        $drlActivitiesPast = [
            [
                'ma' => 'DRL_QK_001',
                'ten' => 'Tham gia tập huấn kỹ năng lãnh đạo',
                'batdau' => $now->copy()->subDays(30)->setHour(8)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(30)->setHour(10)->setMinute(0),
                'diadiem' => 'Phòng hội thảo A1',
                'soluong' => 50,
                'loai' => 'Huấn luyện',
            ],
            [
                'ma' => 'DRL_QK_002',
                'ten' => 'Buổi trao đổi kinh nghiệm với cầu thủ chuyên nghiệp',
                'batdau' => $now->copy()->subDays(25)->setHour(14)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(25)->setHour(16)->setMinute(0),
                'diadiem' => 'Sân vận động trường',
                'soluong' => 100,
                'loai' => 'Giáo dục',
            ],
            [
                'ma' => 'DRL_QK_003',
                'ten' => 'Lớp học về phát triển cá nhân và kỹ năng mềm',
                'batdau' => $now->copy()->subDays(20)->setHour(9)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(20)->setHour(11)->setMinute(0),
                'diadiem' => 'Phòng 201, Tòa A',
                'soluong' => 40,
                'loai' => 'Đào tạo',
            ],
            [
                'ma' => 'DRL_QK_004',
                'ten' => 'Hội thảo về tương lai công nghệ và AI',
                'batdau' => $now->copy()->subDays(15)->setHour(13)->setMinute(30),
                'ketthuc' => $now->copy()->subDays(15)->setHour(15)->setMinute(30),
                'diadiem' => 'Hội trường chính',
                'soluong' => 150,
                'loai' => 'Hội thảo',
            ],
            [
                'ma' => 'DRL_QK_005',
                'ten' => 'Buổi chia sẻ kinh nghiệm học tập hiệu quả',
                'batdau' => $now->copy()->subDays(10)->setHour(10)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(10)->setHour(12)->setMinute(0),
                'diadiem' => 'Phòng 102, Tòa B',
                'soluong' => 60,
                'loai' => 'Chia sẻ kinh nghiệm',
            ],
        ];

        // 5 hoạt động DRL trong tương lai
        $drlActivitiesFuture = [
            [
                'ma' => 'DRL_TL_001',
                'ten' => 'Cuộc thi rắc rối tinh thần lãnh đạo',
                'batdau' => $now->copy()->addDays(5)->setHour(7)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(5)->setHour(11)->setMinute(0),
                'diadiem' => 'Sân thể thao chính',
                'soluong' => 80,
                'loai' => 'Thi đua',
            ],
            [
                'ma' => 'DRL_TL_002',
                'ten' => 'Chương trình tập luyện thể lực tháng 12',
                'batdau' => $now->copy()->addDays(10)->setHour(16)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(10)->setHour(18)->setMinute(0),
                'diadiem' => 'Phòng gym Đại học',
                'soluong' => 35,
                'loai' => 'Thể thao',
            ],
            [
                'ma' => 'DRL_TL_003',
                'ten' => 'Hội thảo về kỹ năng giao tiếp và thuyết trình',
                'batdau' => $now->copy()->addDays(15)->setHour(13)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(15)->setHour(15)->setMinute(30),
                'diadiem' => 'Phòng 301, Tòa A',
                'soluong' => 70,
                'loai' => 'Đào tạo',
            ],
            [
                'ma' => 'DRL_TL_004',
                'ten' => 'Buổi tư vấn học bổng và cơ hội du học',
                'batdau' => $now->copy()->addDays(20)->setHour(10)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(20)->setHour(12)->setMinute(0),
                'diadiem' => 'Hội trường 2',
                'soluong' => 120,
                'loai' => 'Tư vấn',
            ],
            [
                'ma' => 'DRL_TL_005',
                'ten' => 'Chuyên đề về phát triển khả năng lãnh đạo',
                'batdau' => $now->copy()->addDays(25)->setHour(14)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(25)->setHour(16)->setMinute(0),
                'diadiem' => 'Phòng 105, Tòa C',
                'soluong' => 55,
                'loai' => 'Đào tạo',
            ],
        ];

        // Insert DRL Activities
        foreach (array_merge($drlActivitiesPast, $drlActivitiesFuture) as $activity) {
            DB::table('hoatdongdrl')->insert([
                'MaHoatDong' => $activity['ma'],
                'TenHoatDong' => $activity['ten'],
                'MaGV' => $giangVien,
                'MoTa' => 'Hoạt động rèn luyện',
                'ThoiGianBatDau' => $activity['batdau'],
                'ThoiGianKetThuc' => $activity['ketthuc'],
                'DiaDiem' => $activity['diadiem'],
                'SoLuong' => $activity['soluong'],
                'LoaiHoatDong' => $activity['loai'],
                'MaHocKy' => $hocKy,
                'MaQuyDinhDiem' => $quyDinhDRL,
                'CheckInToken' => Str::random(64),
                'CheckInOpenAt' => $activity['batdau']->copy()->subMinutes(30),
                'CheckInExpiresAt' => $activity['batdau']->copy()->addHours(1),
                'CheckOutToken' => Str::random(64),
                'CheckOutOpenAt' => $activity['ketthuc']->copy()->subMinutes(15),
                'CheckOutExpiresAt' => $activity['ketthuc']->copy()->addMinutes(30),
            ]);
        }

        // =============================
        // 10 HOẠT ĐỘNG CTXH
        // =============================

        // 5 hoạt động CTXH trong quá khứ
        $ctxhActivitiesPast = [
            [
                'ma' => 'CTXH_QK_001',
                'ten' => 'Ngày hội tình nguyện 2025 - Vệ sinh cộng đồng',
                'batdau' => $now->copy()->subDays(35)->setHour(7)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(35)->setHour(11)->setMinute(0),
                'diadiem' => 'Đường Nguyễn Huệ, Quận 1',
                'soluong' => 120,
                'loai' => 'Tình nguyện',
            ],
            [
                'ma' => 'CTXH_QK_002',
                'ten' => 'Hỗ trợ giáo dục cho trẻ em vùng sâu',
                'batdau' => $now->copy()->subDays(28)->setHour(8)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(28)->setHour(16)->setMinute(0),
                'diadiem' => 'Xã Nước Hai, Vĩnh Phúc',
                'soluong' => 45,
                'loai' => 'Giáo dục',
            ],
            [
                'ma' => 'CTXH_QK_003',
                'ten' => 'Chương trình hỗ trợ người vô gia cư',
                'batdau' => $now->copy()->subDays(22)->setHour(14)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(22)->setHour(18)->setMinute(0),
                'diadiem' => 'Khu chợ Bến Thành',
                'soluong' => 80,
                'loai' => 'Xã hội',
            ],
            [
                'ma' => 'CTXH_QK_004',
                'ten' => 'Bảo vệ môi trường - Sạch bãi biển',
                'batdau' => $now->copy()->subDays(18)->setHour(6)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(18)->setHour(10)->setMinute(0),
                'diadiem' => 'Bãi biển Vũng Tàu',
                'soluong' => 100,
                'loai' => 'Môi trường',
            ],
            [
                'ma' => 'CTXH_QK_005',
                'ten' => 'Tặng quà Tết cho các gia đình khó khăn',
                'batdau' => $now->copy()->subDays(12)->setHour(9)->setMinute(0),
                'ketthuc' => $now->copy()->subDays(12)->setHour(13)->setMinute(0),
                'diadiem' => 'Các xã ngoại thành',
                'soluong' => 90,
                'loai' => 'Từ thiện',
            ],
        ];

        // 5 hoạt động CTXH trong tương lai
        $ctxhActivitiesFuture = [
            [
                'ma' => 'CTXH_TL_001',
                'ten' => 'Tìm kiếm tài năng công dân trẻ tháng 12',
                'batdau' => $now->copy()->addDays(7)->setHour(14)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(7)->setHour(17)->setMinute(0),
                'diadiem' => 'Hội trường chính',
                'soluong' => 200,
                'loai' => 'Tuyên truyền',
            ],
            [
                'ma' => 'CTXH_TL_002',
                'ten' => 'Hoạt động tình nguyện giáo dục thiếu nhi',
                'batdau' => $now->copy()->addDays(12)->setHour(8)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(12)->setHour(12)->setMinute(0),
                'diadiem' => 'Trường tiểu học Ngô Quyền',
                'soluong' => 50,
                'loai' => 'Giáo dục',
            ],
            [
                'ma' => 'CTXH_TL_003',
                'ten' => 'Chiến dịch bảo vệ môi trường - Thu gom rác thải',
                'batdau' => $now->copy()->addDays(18)->setHour(7)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(18)->setHour(10)->setMinute(0),
                'diadiem' => 'Công viên Tao Đàn',
                'soluong' => 75,
                'loai' => 'Môi trường',
            ],
            [
                'ma' => 'CTXH_TL_004',
                'ten' => 'Chương trình kỹ năng sống cho thanh thiếu niên',
                'batdau' => $now->copy()->addDays(22)->setHour(13)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(22)->setHour(16)->setMinute(0),
                'diadiem' => 'Trung tâm thanh thiếu nhi',
                'soluong' => 60,
                'loai' => 'Đào tạo',
            ],
            [
                'ma' => 'CTXH_TL_005',
                'ten' => 'Hỗ trợ khôi phục vệ sinh công cộng sau mưa bão',
                'batdau' => $now->copy()->addDays(28)->setHour(8)->setMinute(0),
                'ketthuc' => $now->copy()->addDays(28)->setHour(14)->setMinute(0),
                'diadiem' => 'Các tuyến phố thành phố',
                'soluong' => 110,
                'loai' => 'Cộng đồng',
            ],
        ];

        // Insert CTXH Activities
        foreach (array_merge($ctxhActivitiesPast, $ctxhActivitiesFuture) as $activity) {
            DB::table('hoatdongctxh')->insert([
                'MaHoatDong' => $activity['ma'],
                'TenHoatDong' => $activity['ten'],
                'MoTa' => 'Hoạt động xã hội',
                'ThoiGianBatDau' => $activity['batdau'],
                'ThoiGianKetThuc' => $activity['ketthuc'],
                'DiaDiem' => $activity['diadiem'],
                'SoLuong' => $activity['soluong'],
                'LoaiHoatDong' => $activity['loai'],
                'MaQuyDinhDiem' => $quyDinhCTXH,
                'CheckInToken' => Str::random(64),
                'CheckInOpenAt' => $activity['batdau']->copy()->subMinutes(30),
                'CheckInExpiresAt' => $activity['batdau']->copy()->addHours(1),
                'CheckOutToken' => Str::random(64),
                'CheckOutOpenAt' => $activity['ketthuc']->copy()->subMinutes(15),
                'CheckOutExpiresAt' => $activity['ketthuc']->copy()->addMinutes(30),
            ]);
        }

        // =============================
        // THÊM SINH VIÊN ĐĂNG KÝ & ĐIỂM DANH CHO HOẠT ĐỘNG CŨ
        // =============================

        // Chọn 10 hoạt động cũ (5 DRL + 5 CTXH)
        $allPastActivities = array_merge(
            array_map(fn($a) => ['ma' => $a['ma'], 'type' => 'drl'], $drlActivitiesPast),
            array_map(fn($a) => ['ma' => $a['ma'], 'type' => 'ctxh'], $ctxhActivitiesPast)
        );

        // Thêm đăng ký sinh viên cho hoạt động cũ
        foreach ($allPastActivities as $activity) {
            // Chọn 5-8 sinh viên ngẫu nhiên cho mỗi hoạt động
            $numStudents = rand(5, 8);
            $randomStudents = array_slice($sinhViens, 0, $numStudents);

            foreach ($randomStudents as $mssv) {
                $table = $activity['type'] === 'drl' ? 'dangkyhoatdongdrl' : 'dangkyhoatdongctxh';
                $isParticipated = rand(0, 1); // 50% tham gia, 50% không

                DB::table($table)->insert([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $activity['ma'],
                    'NgayDangKy' => $now->copy()->subDays(40),
                    'TrangThaiDangKy' => 'Đã duyệt',
                    'TrangThaiThamGia' => $isParticipated ? 'Đã tham gia' : 'Không tham gia',
                    'CheckInAt' => $isParticipated ? $now->copy()->subDays(40) : null,
                    'CheckOutAt' => $isParticipated ? $now->copy()->subDays(40)->addHours(2) : null,
                ]);
            }
        }

        $this->command->info('✓ Đã tạo 10 hoạt động DRL và 10 hoạt động CTXH thành công!');
        $this->command->line('');
        $this->command->line('📊 Chi tiết:');
        $this->command->line('  • 5 hoạt động DRL trong quá khứ (đã kết thúc)');
        $this->command->line('  • 5 hoạt động DRL trong tương lai');
        $this->command->line('  • 5 hoạt động CTXH trong quá khứ (đã kết thúc)');
        $this->command->line('  • 5 hoạt động CTXH trong tương lai');
        $this->command->line('');
        $this->command->line('✅ Hoạt động cũ có:');
        $this->command->line('  • Sinh viên đăng ký (5-8 SV/hoạt động)');
        $this->command->line('  • Một số đã điểm danh (CheckIn/Out)');
        $this->command->line('  • Một số không tham gia');
    }
}
