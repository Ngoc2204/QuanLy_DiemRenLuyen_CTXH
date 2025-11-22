<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CleanAndReseedDatabase extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear tables but keep quydinh tables
        $this->clearTables();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Seed new data
        $this->seedNewData();
    }

    private function clearTables()
    {
        // Order matters due to foreign keys
        DB::table('bangdiemhocky')->truncate();
        DB::table('chucvusinhvien')->truncate();
        DB::table('diemrenluyen')->truncate();
        DB::table('diemctxh')->truncate();
        DB::table('phanhoisinhvien')->truncate();
        DB::table('ketquathamgiadrl')->truncate();
        DB::table('ketquathamgiactxh')->truncate();
        DB::table('thanh_toan')->truncate();
        DB::table('dangkyhoatdongdrl')->truncate();
        DB::table('dangkyhoatdongctxh')->truncate();
        DB::table('hoatdongdrl')->truncate();
        DB::table('hoatdongctxh')->truncate();
        DB::table('dieuchinhdrl')->truncate();
        DB::table('phan_bo_so_luong')->truncate();
        DB::table('sinhvien')->truncate();
        DB::table('taikhoan')->truncate();
        DB::table('giangvien')->truncate();
        DB::table('nhanvien')->truncate();
        DB::table('covanht')->truncate();
        DB::table('lop')->truncate();
        DB::table('activity_recommendations')->truncate();
        DB::table('student_clusters')->truncate();
        DB::table('cluster_statistics')->truncate();
        DB::table('student_interests')->truncate();
        DB::table('activity_topic_tags')->truncate();
        DB::table('dot_dia_chi_do')->truncate();
        DB::table('diadiemdiachido')->truncate();
    }

    private function seedNewData()
    {
        // Seed locations
        $locations = [
            ['TenDiaDiem' => 'Hội trường A', 'DiaChi' => 'Tòa nhà A, HUST', 'GiaTien' => 50000],
            ['TenDiaDiem' => 'Hội trường B', 'DiaChi' => 'Tòa nhà B, HUST', 'GiaTien' => 75000],
            ['TenDiaDiem' => 'Sân vận động', 'DiaChi' => 'Khu vực sân vận động', 'GiaTien' => 0],
            ['TenDiaDiem' => 'Phòng học C1', 'DiaChi' => 'Tòa C, HUST', 'GiaTien' => 0],
            ['TenDiaDiem' => 'Địa đạo Củ Chi', 'DiaChi' => 'Quận Phú Nhuận, TP.HCM', 'GiaTien' => 150000],
        ];
        foreach ($locations as $loc) {
            DB::table('diadiemdiachido')->insert(array_merge($loc, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Seed activity rounds (đợt hoạt động)
        $rounds = [
            ['TenDot' => 'Đợt 1 - HK1 2025-2026', 'NgayBatDau' => '2025-09-01', 'NgayKetThuc' => '2025-12-31', 'TrangThai' => 'DangDienRa'],
            ['TenDot' => 'Đợt 2 - HK2 2025-2026', 'NgayBatDau' => '2026-01-01', 'NgayKetThuc' => '2026-06-30', 'TrangThai' => 'SapDienRa'],
        ];
        foreach ($rounds as $round) {
            DB::table('dot_dia_chi_do')->insert(array_merge($round, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Seed accounts: 1 Admin, 5 Teachers, 5 Employees, 20 Students
        $accounts = [
            ['TenDangNhap' => 'admin', 'MatKhau' => Hash::make('123456'), 'VaiTro' => 'Admin'],
        ];

        // Teachers
        for ($i = 1; $i <= 5; $i++) {
            $accounts[] = ['TenDangNhap' => 'GV00' . $i, 'MatKhau' => Hash::make('123456'), 'VaiTro' => 'GiangVien'];
        }

        // Employees
        for ($i = 1; $i <= 5; $i++) {
            $accounts[] = ['TenDangNhap' => 'NV00' . $i, 'MatKhau' => Hash::make('123456'), 'VaiTro' => 'NhanVien'];
        }

        // Students
        for ($i = 1; $i <= 20; $i++) {
            $mssv = sprintf('200122%04d', 3000 + $i);
            $accounts[] = ['TenDangNhap' => $mssv, 'MatKhau' => Hash::make('123456'), 'VaiTro' => 'SinhVien'];
        }

        foreach ($accounts as $account) {
            DB::table('taikhoan')->insertOrIgnore($account);
        }

        // Seed teachers
        for ($i = 1; $i <= 5; $i++) {
            DB::table('giangvien')->insert([
                'MaGV' => 'GV00' . $i,
                'TenGV' => 'Giảng viên ' . $i,
                'Email' => 'gv' . $i . '@hust.edu.vn',
                'SDT' => '090000000' . $i,
                'GioiTinh' => ($i % 2 == 0) ? 'Nữ' : 'Nam',
            ]);
        }

        // Seed employees
        for ($i = 1; $i <= 5; $i++) {
            DB::table('nhanvien')->insert([
                'MaNV' => 'NV00' . $i,
                'TenNV' => 'Nhân viên ' . $i,
                'Email' => 'nv' . $i . '@hust.edu.vn',
                'SDT' => '089000000' . $i,
                'GioiTinh' => ($i % 2 == 0) ? 'Nữ' : 'Nam',
            ]);
        }

        // Seed classes
        DB::table('lop')->insert([
            'MaLop' => 'K65A1',
            'TenLop' => 'Lớp 65A1 - CNTT',
            'MaKhoa' => 'CNTT',
        ]);
        DB::table('lop')->insert([
            'MaLop' => 'K65A2',
            'TenLop' => 'Lớp 65A2 - CNTT',
            'MaKhoa' => 'CNTT',
        ]);
        DB::table('lop')->insert([
            'MaLop' => 'K65B1',
            'TenLop' => 'Lớp 65B1 - CNTT',
            'MaKhoa' => 'CNTT',
        ]);

        // Seed class advisors
        DB::table('covanht')->insert(['MaLop' => 'K65A1', 'MaGiangVien' => 'GV001']);
        DB::table('covanht')->insert(['MaLop' => 'K65A2', 'MaGiangVien' => 'GV002']);
        DB::table('covanht')->insert(['MaLop' => 'K65B1', 'MaGiangVien' => 'GV003']);

        // Seed 20 students
        $classes = ['K65A1', 'K65A2', 'K65B1'];
        for ($i = 1; $i <= 20; $i++) {
            $mssv = sprintf('200122%04d', 3000 + $i);
            $class = $classes[($i - 1) % 3];
            DB::table('sinhvien')->insert([
                'MSSV' => $mssv,
                'HoTen' => 'Sinh viên ' . $i,
                'Email' => 'sv' . $i . '@hust.edu.vn',
                'SDT' => '098000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'NgaySinh' => Carbon::createFromDate(2003, rand(1, 12), rand(1, 28)),
                'GioiTinh' => ($i % 2 == 0) ? 'Nữ' : 'Nam',
                'MaLop' => $class,
                'ThoiGianTotNghiepDuKien' => '2026-12-31',
                'SoThich' => 'Thể thao, Lập trình',
            ]);

            // Initialize CTXH score
            DB::table('diemctxh')->insert([
                'MSSV' => $mssv,
                'TongDiem' => 0,
                'NgayCapNhat' => now(),
            ]);

            // Initialize DRL score
            DB::table('diemrenluyen')->insert([
                'MSSV' => $mssv,
                'MaHocKy' => 'HK1_2526',
                'TongDiem' => rand(60, 100),
                'XepLoai' => $this->getClassification(rand(60, 100)),
                'NgayCapNhat' => now(),
            ]);
        }

        // Seed DRL activities (20 activities)
        $drlActivities = [
            'Hội thảo Lập trình Python',
            'Hackathon HUST 2025',
            'Workshop AI/ML',
            'Cuộc thi Lập trình Web',
            'Seminar Khởi nghiệp Công nghệ',
            'Thi Olympic Tin học',
            'Đội tuyển Bóng đá',
            'Đội tuyển Cầu lông',
            'Đội tuyển Bóng bàn',
            'Đội tuyển Đàn ong',
            'Cuộc thi Thiết kế UI/UX',
            'Workshop Git & GitHub',
            'Hội thảo Bảo mật Thông tin',
            'Cuộc thi Robot tự trị',
            'Sự kiện Chúc mừng Ngày Nhà giáo',
            'CLB Công nghệ & Khởi nghiệp',
            'Hỗ trợ tuyển sinh 2026',
            'Đại hội Đoàn viên Thanh niên',
            'Workshop Kỹ năng mềm cho IT',
            'Sự kiện Tết sum vầy',
        ];

        for ($i = 0; $i < 20; $i++) {
            $maHoatDong = 'DRL-' . Carbon::now()->format('Ymd') . '-' . Str::random(4);
            DB::table('hoatdongdrl')->insert([
                'MaHoatDong' => $maHoatDong,
                'TenHoatDong' => $drlActivities[$i],
                'MaGV' => 'GV00' . (($i % 5) + 1),
                'MoTa' => 'Mô tả cho hoạt động: ' . $drlActivities[$i],
                'ThoiGianBatDau' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 16), 0),
                'ThoiGianKetThuc' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(17, 20), 0),
                'ThoiHanHuy' => Carbon::now()->addDays(rand(1, 20)),
                'DiaDiem' => ['Hội trường A', 'Hội trường B', 'Sân vận động', 'Phòng học C1'][rand(0, 3)],
                'SoLuong' => rand(30, 100),
                'LoaiHoatDong' => ['Học tập', 'Thể dục - Thể thao', 'Văn hóa - Văn nghệ', 'Cộng đồng', 'Khác'][rand(0, 4)],
                'MaHocKy' => 'HK1_2526',
                'MaQuyDinhDiem' => 'DRL0' . (($i % 5) + 1),
            ]);
        }

        // Seed CTXH activities (20 activities)
        $ctxhActivities = [
            'Địa chỉ đỏ - Tham quan Địa đạo Củ Chi',
            'Tình nguyện Dọn vệ sinh công cộng',
            'Tình nguyện Hỗ trợ trẻ em khuyết tật',
            'Văn nghệ Gặp mặt kỷ niệm ngành CNTT',
            'Hội thảo Bảo vệ môi trường',
            'Hoạt động Chủ nhật xanh',
            'Tiếp sức mùa thi',
            'Hỗ trợ Công tác an ninh trật tự',
            'Văn nghệ Ngày Lễ Tết',
            'Tham dự Tọa đàm Luật pháp',
            'Hoạt động Lớp học không rác',
            'Tình nguyện Thăm viếng Trung tâm bảo trợ xã hội',
            'Hành trình về nguồn',
            'Olympic Toán - Khoa học',
            'Cuộc thi Kỹ năng - Tay nghề',
            'Tham gia các sự kiện văn hóa trường',
            'Trực KTX - Hỗ trợ trật tự ký túc xá',
            'Hỗ trợ công tác Quản lý học viên',
            'Tiếp sức công tác Tuyển sinh',
            'Hoạt động Sân trường sạch đẹp',
        ];

        for ($i = 0; $i < 20; $i++) {
            $maHoatDong = 'CTXH-' . Carbon::now()->format('Ymd') . '-' . Str::random(4);
            $diadiem_id = ($i % 2 == 0) ? 1 : (($i % 3 == 0) ? 5 : 3);
            $dot_id = ($i < 10) ? 1 : 2;
            
            DB::table('hoatdongctxh')->insert([
                'MaHoatDong' => $maHoatDong,
                'TenHoatDong' => $ctxhActivities[$i],
                'dot_id' => $dot_id,
                'diadiem_id' => $diadiem_id,
                'MoTa' => 'Mô tả cho hoạt động: ' . $ctxhActivities[$i],
                'ThoiGianBatDau' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(7, 15), 0),
                'ThoiGianKetThuc' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(16, 18), 0),
                'ThoiHanHuy' => Carbon::now()->addDays(rand(1, 20)),
                'DiaDiem' => ['Hội trường A', 'Hội trường B', 'Sân vận động', 'Địa đạo Củ Chi', 'Phòng học C1'][rand(0, 4)],
                'SoLuong' => rand(30, 200),
                'LoaiHoatDong' => ['Tình nguyện', 'Văn hóa - Văn nghệ', 'Hội thảo', 'Tiếp sức mùa thi', 'Khác'][rand(0, 4)],
                'MaQuyDinhDiem' => 'CTXH' . str_pad(($i % 15) + 1, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // Register students in activities
        $this->registerStudentsInActivities();

        // Add some check-in/check-out records
        $this->addCheckInCheckOut();

        $this->command->info('✅ Database seeded successfully!');
    }

    private function getClassification($score)
    {
        if ($score >= 90) return 'Xuất Sắc';
        if ($score >= 80) return 'Giỏi';
        if ($score >= 70) return 'Khá';
        if ($score >= 60) return 'Trung Bình';
        if ($score >= 50) return 'Yếu';
        return 'Kém';
    }

    private function registerStudentsInActivities()
    {
        $drlActivities = DB::table('hoatdongdrl')->pluck('MaHoatDong')->toArray();
        $ctxhActivities = DB::table('hoatdongctxh')->pluck('MaHoatDong')->toArray();
        $students = DB::table('sinhvien')->pluck('MSSV')->toArray();

        // Each student registers for 3-5 DRL activities
        foreach ($students as $mssv) {
            $numDrl = rand(3, 5);
            $selectedDrl = array_rand($drlActivities, $numDrl);
            if (!is_array($selectedDrl)) $selectedDrl = [$selectedDrl];

            foreach ($selectedDrl as $idx) {
                DB::table('dangkyhoatdongdrl')->insert([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $drlActivities[$idx],
                    'NgayDangKy' => now()->subDays(rand(5, 20)),
                    'TrangThaiDangKy' => ['Chờ duyệt', 'Đã duyệt', 'Từ chối'][rand(0, 2)],
                    'TrangThaiThamGia' => 'Chưa tham gia',
                ]);
            }

            // Each student registers for 2-4 CTXH activities
            $numCtxh = rand(2, 4);
            $selectedCtxh = array_rand($ctxhActivities, $numCtxh);
            if (!is_array($selectedCtxh)) $selectedCtxh = [$selectedCtxh];

            foreach ($selectedCtxh as $idx) {
                DB::table('dangkyhoatdongctxh')->insert([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $ctxhActivities[$idx],
                    'NgayDangKy' => now()->subDays(rand(5, 20)),
                    'TrangThaiDangKy' => ['Chờ duyệt', 'Đã duyệt', 'Chờ thanh toán'][rand(0, 2)],
                    'NgayThamGia' => rand(0, 1) ? now()->subDays(rand(1, 10)) : null,
                    'TrangThaiThamGia' => ['Chưa tham gia', 'Đang tham gia', 'Đã tham gia'][rand(0, 2)],
                ]);
            }
        }
    }

    private function addCheckInCheckOut()
    {
        // Get some registered activities and add check-in/checkout tokens
        $drlRegs = DB::table('dangkyhoatdongdrl')
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->limit(10)
            ->get();

        foreach ($drlRegs as $reg) {
            if (rand(0, 1)) {
                DB::table('dangkyhoatdongdrl')
                    ->where('MaDangKy', $reg->MaDangKy)
                    ->update([
                        'CheckInAt' => now()->subHours(rand(1, 5)),
                        'TrangThaiThamGia' => 'Đã tham gia',
                    ]);

                // Randomly add CheckOut
                if (rand(0, 1)) {
                    DB::table('dangkyhoatdongdrl')
                        ->where('MaDangKy', $reg->MaDangKy)
                        ->update([
                            'CheckOutAt' => now()->subHours(rand(0, 3)),
                        ]);
                }
            }
        }

        $ctxhRegs = DB::table('dangkyhoatdongctxh')
            ->where('TrangThaiDangKy', 'Đã duyệt')
            ->limit(10)
            ->get();

        foreach ($ctxhRegs as $reg) {
            if (rand(0, 1)) {
                DB::table('dangkyhoatdongctxh')
                    ->where('MaDangKy', $reg->MaDangKy)
                    ->update([
                        'CheckInAt' => now()->subHours(rand(1, 5)),
                        'TrangThaiThamGia' => 'Đã tham gia',
                    ]);

                if (rand(0, 1)) {
                    DB::table('dangkyhoatdongctxh')
                        ->where('MaDangKy', $reg->MaDangKy)
                        ->update([
                            'CheckOutAt' => now()->subHours(rand(0, 3)),
                        ]);
                }
            }
        }
    }
}
