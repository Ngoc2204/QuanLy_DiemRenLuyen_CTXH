<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudentsAndAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Danh sách các lớp thực tế trong database
        $classes = [
            '12DHCK01', '13DHTP01', '13DHTP02', '14DHTP05', '15DHTP01',
            '12DHTH01', '13DHTH05', '13DHTH06', '14DHAT01', '14DHTH02',
            '13DHDT04', '13DHDL01', '14DHKS02', '13DHLK01', '14DHNA01',
            '14DHTQ01', '12DHQT02', '13DHMK01', '13DHKT03', '14DHTC01',
            '13DHTM01', '14DHTM03'
        ];

        // Danh sách tên học sinh
        $firstNames = ['Nguyễn', 'Trần', 'Phạm', 'Lê', 'Võ', 'Bùi', 'Đặng', 'Ngô', 'Huỳnh', 'Trịnh', 'Hoàng', 'Tạ', 'Tô', 'Phan', 'Dương'];
        $middleNames = ['Tất', 'Minh', 'Nhật', 'Thị', 'Thành', 'Khánh', 'Quốc', 'Ngọc', 'Gia', 'Hồng', 'Thanh', 'Hoài', 'Diễm', 'Hưởng', 'Văn', 'Thế', 'Mạnh', 'Hưng', 'Tâm', 'Bảo', 'Phúc', 'Hà', 'Tùng', 'Sơn', 'Minh'];
        $lastNames = ['Ngọc', 'Khoa', 'Huy', 'Duyên', 'Đạt', 'Linh', 'Huy', 'Thư', 'Bảo', 'Lan', 'Long', 'Trúc', 'Hà', 'Thành', 'Thảo', 'My', 'Tâm', 'Phú', 'Tiến', 'Lợi', 'Trị', 'Hợp', 'Thông', 'Kiên', 'Vũ'];
        
        $mssv = 2001223001;
        $phoneNumber = 901000001;

        foreach ($classes as $classIndex => $className) {
            for ($i = 0; $i < 10; $i++) {
                // Tạo tên ngẫu nhiên
                $firstName = $firstNames[array_rand($firstNames)];
                $middleName = $middleNames[array_rand($middleNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = "$firstName $middleName $lastName";

                $email = $mssv . '@huit.edu.vn';
                $phone = '09' . str_pad($phoneNumber, 8, '0', STR_PAD_LEFT);
                $mssvStr = (string)$mssv;

                // Insert tài khoản trước (QUAN TRỌNG: ForeignKey)
                DB::table('taikhoan')->insert([
                    'TenDangNhap' => $mssvStr,
                    'MatKhau' => Hash::make('123456'),
                    'VaiTro' => 'SinhVien'
                ]);

                // Sau đó insert sinh viên
                DB::table('sinhvien')->insert([
                    'MSSV' => $mssvStr,
                    'HoTen' => $fullName,
                    'Email' => $email,
                    'SDT' => $phone,
                    'NgaySinh' => '2004-' . str_pad(($classIndex * 10 + $i) % 12 + 1, 2, '0', STR_PAD_LEFT) . '-' . str_pad(($i + 1), 2, '0', STR_PAD_LEFT),
                    'GioiTinh' => $i % 2 == 0 ? 'Nam' : 'Nữ',
                    'MaLop' => $className
                ]);

                $mssv++;
                $phoneNumber++;
            }
        }

        $this->command->info('✓ Đã tạo ' . count($classes) * 10 . ' sinh viên và ' . count($classes) * 10 . ' tài khoản thành công!');
        $this->command->line('MSSV: 2001223001 → 2001223' . sprintf('%03d', count($classes) * 10));
        $this->command->line('Mật khẩu mặc định: 123456');
        $this->command->line('Vai trò: SinhVien');
        $this->command->line('Số lớp: ' . count($classes));
    }
}
