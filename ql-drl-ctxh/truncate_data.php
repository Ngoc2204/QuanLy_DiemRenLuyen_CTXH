<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0');

$tables = DB::select('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?', [env('DB_DATABASE')]);

foreach ($tables as $table) {
    $tableName = $table->TABLE_NAME;
    if (!in_array($tableName, ['migrations', 'migrations_lock'])) {
        echo "Xóa dữ liệu từ bảng: {$tableName}\n";
        DB::table($tableName)->truncate();
    }
}

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n✅ Xóa dữ liệu xong! (Giữ lại bảng migrations)\n";
