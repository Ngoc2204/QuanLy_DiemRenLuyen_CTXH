<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hash all existing plain text passwords
        $users = DB::table('taikhoan')->get();
        
        foreach ($users as $user) {
            // Skip if already hashed (bcrypt passwords start with $2y$)
            if (!str_starts_with($user->MatKhau, '$2y$')) {
                DB::table('taikhoan')
                    ->where('TenDangNhap', $user->TenDangNhap)
                    ->update(['MatKhau' => Hash::make($user->MatKhau)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot safely reverse password hashing
        // This migration should not be rolled back in production
    }
};
