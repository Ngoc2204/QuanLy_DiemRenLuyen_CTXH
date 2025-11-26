<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$users = DB::table('taikhoan')->get();

if ($users->isEmpty()) {
    echo "✅ Không có dữ liệu trong bảng taikhoan\n";
    exit;
}

DB::statement('SET FOREIGN_KEY_CHECKS=0');

foreach ($users as $user) {
    // Kiểm tra nếu chưa phải hash (không bắt đầu với $2y$ hoặc $2a$ hoặc $2b$)
    if (!preg_match('/^\$2[aby]\$/', $user->MatKhau)) {
        echo "Đang hash password cho: {$user->TenDangNhap}\n";
        DB::table('taikhoan')
            ->where('TenDangNhap', $user->TenDangNhap)
            ->update(['MatKhau' => Hash::make($user->MatKhau)]);
    } else {
        echo "✅ Đã hash: {$user->TenDangNhap}\n";
    }
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n✅ Hoàn thành! Tất cả password đã được hash.\n";
