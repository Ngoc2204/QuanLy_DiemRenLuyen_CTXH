<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagActivityCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map activity names to interest IDs
        // 1: Thể thao, 2: Âm nhạc, 3: Nghệ thuật, 4: Tình nguyện, 5: Khoa học, 
        // 6: Ngoại ngữ, 7: Kinh doanh, 8: Giáo dục, 9: Du lịch, 10: Nấu ăn

        $drlTags = [
            'Hội thảo Lập trình Python' => '5,8',         // Khoa học, Giáo dục
            'Hackathon HUST 2025' => '5,7',                // Khoa học, Kinh doanh
            'Workshop AI/ML' => '5',                       // Khoa học
            'Cuộc thi Lập trình Web' => '5',              // Khoa học
            'Seminar Khởi nghiệp Công nghệ' => '5,7',     // Khoa học, Kinh doanh
            'Thi Olympic Tin học' => '5,8',               // Khoa học, Giáo dục
            'Đội tuyển Bóng đá' => '1',                    // Thể thao
            'Đội tuyển Cầu lông' => '1',                   // Thể thao
            'Đội tuyển Bóng bàn' => '1',                   // Thể thao
            'Đội tuyển Đàn ong' => '1',                    // Thể thao
            'Cuộc thi Thiết kế UI/UX' => '3,5',           // Nghệ thuật, Khoa học
            'Workshop Git & GitHub' => '5,8',              // Khoa học, Giáo dục
            'Hội thảo Bảo mật Thông tin' => '5',          // Khoa học
            'Cuộc thi Robot tự trị' => '5',               // Khoa học
            'Sự kiện Chúc mừng Ngày Nhà giáo' => '8',     // Giáo dục
            'CLB Công nghệ & Khởi nghiệp' => '5,7',       // Khoa học, Kinh doanh
            'Hỗ trợ tuyển sinh 2026' => '8',              // Giáo dục
            'Đại hội Đoàn viên Thanh niên' => '4',        // Tình nguyện
            'Workshop Kỹ năng mềm cho IT' => '5,8',        // Khoa học, Giáo dục
            'Sự kiện Tết sum vầy' => '2,3',               // Âm nhạc, Nghệ thuật
        ];

        $ctxhTags = [
            'Địa chỉ đỏ - Tham quan Địa đạo Củ Chi' => '4,9',           // Tình nguyện, Du lịch
            'Tình nguyện Dọn vệ sinh công cộng' => '4',                 // Tình nguyện
            'Tình nguyện Hỗ trợ trẻ em khuyết tật' => '4',              // Tình nguyện
            'Văn nghệ Gặp mặt kỷ niệm ngành CNTT' => '2,3',             // Âm nhạc, Nghệ thuật
            'Hội thảo Bảo vệ môi trường' => '4,5',                      // Tình nguyện, Khoa học
            'Hoạt động Chủ nhật xanh' => '4',                           // Tình nguyện
            'Tiếp sức mùa thi' => '4,8',                                // Tình nguyện, Giáo dục
            'Hỗ trợ Công tác an ninh trật tự' => '4',                   // Tình nguyện
            'Văn nghệ Ngày Lễ Tết' => '2,3',                            // Âm nhạc, Nghệ thuật
            'Tọa đàm Luật pháp' => '5,8',                               // Khoa học, Giáo dục
            'Hoạt động Lớp học không rác' => '4',                       // Tình nguyện
            'Tình nguyện Thăm viếng Trung tâm bảo trợ xã hội' => '4',   // Tình nguyện
            'Hành trình về nguồn' => '4,9',                             // Tình nguyện, Du lịch
            'Olympic Toán - Khoa học' => '5,8',                         // Khoa học, Giáo dục
            'Cuộc thi Kỹ năng - Tay nghề' => '5',                       // Khoa học
            'Tham gia các sự kiện văn hóa trường' => '2,3',             // Âm nhạc, Nghệ thuật
            'Trực KTX - Hỗ trợ trật tự ký túc xá' => '4',              // Tình nguyện
            'Hỗ trợ công tác Quản lý học viên' => '4',                 // Tình nguyện
            'Tiếp sức công tác Tuyển sinh' => '4,8',                    // Tình nguyện, Giáo dục
            'Hoạt động Sân trường sạch đẹp' => '4',                     // Tình nguyện
        ];

        // Update DRL activities
        foreach ($drlTags as $name => $tags) {
            DB::table('hoatdongdrl')
                ->where('TenHoatDong', 'like', '%' . substr($name, 0, 20) . '%')
                ->update(['category_tags' => $tags]);
        }

        // Update CTXH activities
        foreach ($ctxhTags as $name => $tags) {
            DB::table('hoatdongctxh')
                ->where('TenHoatDong', 'like', '%' . substr($name, 0, 20) . '%')
                ->update(['category_tags' => $tags]);
        }

        $this->command->info('✅ Tagged activities with interest categories');
    }
}
