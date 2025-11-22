<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\StudentInterest;

class PopulateStudentInterestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Interest mapping: InterestID => Name
        $interests = [
            1 => 'Thể thao',
            2 => 'Âm nhạc',
            3 => 'Nghệ thuật',
            4 => 'Tình nguyện',
            5 => 'Khoa học & Công nghệ',
            6 => 'Ngoại ngữ',
            7 => 'Kinh doanh',
            8 => 'Giáo dục',
            9 => 'Du lịch',
            10 => 'Nấu ăn',
        ];

        $students = DB::table('sinhvien')->pluck('MSSV')->toArray();

        foreach ($students as $mssv) {
            // Sinh viên 20011223001: CNTT - Sở thích Khoa học, Lập trình, Ngoại ngữ
            if ($mssv === '200122300' || strpos($mssv, '3001') !== false) {
                $this->addInterest($mssv, 5, 5); // Khoa học & Công nghệ - Level 5
                $this->addInterest($mssv, 6, 4); // Ngoại ngữ - Level 4
                $this->addInterest($mssv, 7, 3); // Kinh doanh - Level 3
            }
            // Sinh viên 20011223002: Quản trị kinh doanh - Nghe nhạc, đọc sách, chơi game
            else if ($mssv === '200122300' || strpos($mssv, '3002') !== false) {
                $this->addInterest($mssv, 2, 5); // Âm nhạc - Level 5
                $this->addInterest($mssv, 8, 4); // Giáo dục (Đọc sách) - Level 4
                $this->addInterest($mssv, 5, 3); // Khoa học (Game) - Level 3
                $this->addInterest($mssv, 3, 3); // Nghệ thuật - Level 3
            }
            // Sinh viên 20011223003: Kiến trúc - Thể thao, Nghệ thuật, Tình nguyện
            else if ($mssv === '200122300' || strpos($mssv, '3003') !== false) {
                $this->addInterest($mssv, 1, 5); // Thể thao - Level 5
                $this->addInterest($mssv, 3, 4); // Nghệ thuật - Level 4
                $this->addInterest($mssv, 4, 4); // Tình nguyện - Level 4
            }
            // Các sinh viên khác: Random interests
            else {
                $selectedInterests = array_rand(array_flip(range(1, 10)), rand(3, 5));
                foreach ((array)$selectedInterests as $interestId) {
                    $this->addInterest($mssv, $interestId, rand(2, 5));
                }
            }
        }

        $this->command->info('✅ Populated student interests');
    }

    private function addInterest($mssv, $interestId, $level)
    {
        DB::table('student_interests')->updateOrInsert(
            [
                'MSSV' => $mssv,
                'InterestID' => $interestId,
            ],
            [
                'InterestLevel' => $level,
                'UpdatedAt' => now(),
            ]
        );
    }
}
