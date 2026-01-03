<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpandActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds - Add 20 more activities with varied participation
     */
    public function run(): void
    {
        $now = Carbon::now();
        $sinhViens = DB::table('sinhvien')->pluck('MSSV')->toArray();
        $quyDinhDRL = 'DRL01';
        $quyDinhCTXH = 'CTXH26';

        // Create 10 more DRL activities with past dates (for participation)
        $drlActivities = [];
        for ($i = 1; $i <= 10; $i++) {
            $drlActivities[] = [
                'MaHoatDong' => 'DRL_EXT_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'TenHoatDong' => "Hoạt động DRL mở rộng $i - " . ["Kỹ năng", "Thể thao", "Lãnh đạo", "Sáng tạo", "Công nghệ", "Trí tuệ", "Ngoại ngữ", "Giáo dục", "Kinh doanh", "Môi trường"][$i % 10],
                'MoTa' => "Mô tả chi tiết cho hoạt động DRL mở rộng $i",
                'ThoiGianBatDau' => $now->copy()->subDays(70 - ($i * 3))->setHour(8)->setMinute(0),
                'ThoiGianKetThuc' => $now->copy()->subDays(70 - ($i * 3))->setHour(10)->setMinute(0),
                'DiaDiem' => "Phòng " . (100 + $i),
                'SoLuong' => 100,
                'LoaiHoatDong' => 'Huấn luyện',
                'MaHocKy' => 'HK1_2526',
                'MaQuyDinhDiem' => $quyDinhDRL,
                'MaGV' => 'GV001',
                'category_tags' => 'drl,training,skill',
            ];
        }

        // Create 10 more CTXH activities
        $ctxhActivities = [];
        for ($i = 1; $i <= 10; $i++) {
            $ctxhActivities[] = [
                'MaHoatDong' => 'CTXH_EXT_' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'TenHoatDong' => "Hoạt động CTXH mở rộng $i - " . ["Xã hội", "Tình nguyện", "Cộng đồng", "Môi trường", "Giáo dục", "Y tế", "Văn hóa", "Thể thao", "Khoa học", "Công nghệ"][$i % 10],
                'MoTa' => "Mô tả chi tiết cho hoạt động CTXH mở rộng $i",
                'ThoiGianBatDau' => $now->copy()->subDays(70 - ($i * 3))->setHour(8)->setMinute(0),
                'ThoiGianKetThuc' => $now->copy()->subDays(70 - ($i * 3))->setHour(10)->setMinute(0),
                'DiaDiem' => "Địa điểm CTXH $i",
                'SoLuong' => 80,
                'LoaiHoatDong' => 'Tình nguyện',
                'MaQuyDinhDiem' => $quyDinhCTXH,
                'category_tags' => 'ctxh,volunteer,community',
            ];
        }

        // Insert activities
        DB::table('hoatdongdrl')->insert($drlActivities);
        DB::table('hoatdongctxh')->insert($ctxhActivities);

        // Create varied participation - not all students do all activities
        $drlActivityIds = array_column($drlActivities, 'MaHoatDong');
        $ctxhActivityIds = array_column($ctxhActivities, 'MaHoatDong');

        // For DRL: Each activity has 60-80% of students participating
        foreach ($drlActivityIds as $index => $actId) {
            $participationRate = 0.6 + (($index % 5) * 0.04); // 60%, 64%, 68%, 72%, 76%, etc
            $count = intval(count($sinhViens) * $participationRate);
            $shuffled = $sinhViens;
            shuffle($shuffled);
            $participants = array_slice($shuffled, 0, $count);

            foreach ($participants as $mssv) {
                DB::table('dangkyhoatdongdrl')->insert([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $actId,
                    'NgayDangKy' => now()->subDays(71 - ($index * 3)),
                    'TrangThaiDangKy' => 'Đã duyệt',
                    'CheckInAt' => now()->subDays(70 - ($index * 3))->setHour(8)->setMinute(5),
                    'CheckOutAt' => now()->subDays(70 - ($index * 3))->setHour(9)->setMinute(55),
                    'TrangThaiThamGia' => 'Đã tham gia',
                ]);
            }
        }

        // For CTXH: Similar varied participation
        foreach ($ctxhActivityIds as $index => $actId) {
            $participationRate = 0.55 + (($index % 6) * 0.05); // 55%, 60%, 65%, 70%, 75%, 80%
            $count = intval(count($sinhViens) * $participationRate);
            $shuffled = $sinhViens;
            shuffle($shuffled);
            $participants = array_slice($shuffled, 0, $count);

            foreach ($participants as $mssv) {
                DB::table('dangkyhoatdongctxh')->insert([
                    'MSSV' => $mssv,
                    'MaHoatDong' => $actId,
                    'NgayDangKy' => now()->subDays(71 - ($index * 3)),
                    'TrangThaiDangKy' => 'Đã duyệt',
                    'CheckInAt' => now()->subDays(70 - ($index * 3))->setHour(8)->setMinute(5),
                    'CheckOutAt' => now()->subDays(70 - ($index * 3))->setHour(9)->setMinute(55),
                    'TrangThaiThamGia' => 'Đã tham gia',
                ]);
            }
        }

        $this->command->info('✅ Expanded activities created: 10 DRL + 10 CTXH with varied participation');
        $this->command->info('Total DRL activities: ' . DB::table('hoatdongdrl')->count());
        $this->command->info('Total CTXH activities: ' . DB::table('hoatdongctxh')->count());
    }
}
