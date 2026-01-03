<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateNamNhapHocSeeder extends Seeder
{
    /**
     * Populate NamNhapHoc từ MaLop cho các sinh viên không có giá trị
     * 
     * Logic:
     * 1. Trích xuất 2 ký tự đầu của MaLop (VD: "13DHTH06" -> 13)
     * 2. Tính năm nhập học: 2010 + (cohort - 1)
     *    - K1 (13) -> 2010 + 12 = 2022
     *    - K2 (14) -> 2010 + 13 = 2023
     *    - K3 (15) -> 2010 + 14 = 2024
     *    - vv.
     * 
     * FIX: Dùng base year có thể điều chỉnh
     */
    public function run(): void
    {
        $students = DB::table('sinhvien')
            ->whereNull('NamNhapHoc')
            ->orWhere('NamNhapHoc', 0)
            ->get();

        $baseYear = 2010; // Base year - điều chỉnh nếu cần

        foreach ($students as $student) {
            // Trích xuất khóa từ 2 ký tự đầu MaLop
            $cohort = intval(substr($student->MaLop, 0, 2));
            $namNhapHoc = $baseYear + ($cohort - 1);

            // Update database
            DB::table('sinhvien')
                ->where('MSSV', $student->MSSV)
                ->update([
                    'NamNhapHoc' => $namNhapHoc,
                ]);

            $this->command->info("Updated {$student->MSSV}: NamNhapHoc = {$namNhapHoc}");
        }

        $this->command->info('✅ PopulateNamNhapHoc hoàn tất!');
    }
}
