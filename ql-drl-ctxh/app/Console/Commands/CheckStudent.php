<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckStudent extends Command
{
    protected $signature = 'check:student {mssv}';
    protected $description = 'Check student recommendations';

    public function handle()
    {
        $mssv = $this->argument('mssv');

        echo "=== STUDENT $mssv ===\n\n";

        $student = DB::table('sinhvien')->where('MSSV', $mssv)->first();
        if (!$student) {
            $this->error("Student not found!");
            return 1;
        }

        $this->line("Student: {$student->HoTen} - $mssv");
        $this->line("Faculty: {$student->MaKhoa}");
        $this->line("Class: {$student->MaLop}\n");

        $cluster = DB::table('student_clusters')->where('MSSV', $mssv)->first();
        if ($cluster) {
            $this->line("Cluster: {$cluster->ClusterID}");
            $this->line("Cluster Name: {$cluster->ClusterName}\n");
        } else {
            $this->error("No cluster assigned!\n");
        }

        $drlParticipated = DB::table('dangkyhoatdongdrl')->where('MSSV', $mssv)->where('TrangThaiThamGia', 'Đã tham gia')->count();
        $ctxhParticipated = DB::table('dangkyhoatdongctxh')->where('MSSV', $mssv)->where('TrangThaiThamGia', 'Đã tham gia')->count();
        
        $this->line("Participated: DRL=$drlParticipated, CTXH=$ctxhParticipated");
        $this->line($totalParticipated = $drlParticipated + $ctxhParticipated);
        
        if ($totalParticipated < 5) {
            $this->line("Phase: COLD START\n");
        } else {
            $this->line("Phase: REFINEMENT\n");
        }

        $recommendations = DB::table('activity_recommendations')->where('MSSV', $mssv)->limit(15)->get();
        $this->line("=== RECOMMENDATIONS (" . count($recommendations) . " items) ===\n");

        $duplicateCount = 0;
        foreach ($recommendations as $rec) {
            if ($rec->activity_type === 'drl') {
                $reg = DB::table('dangkyhoatdongdrl')->where('MSSV', $mssv)->where('MaHoatDong', $rec->MaHoatDong)->first();
            } else {
                $reg = DB::table('dangkyhoatdongctxh')->where('MSSV', $mssv)->where('MaHoatDong', $rec->MaHoatDong)->first();
            }

            if ($reg) {
                $this->error("{$rec->MaHoatDong} ({$rec->activity_type}) - Score: {$rec->recommendation_score} ⚠ ALREADY REGISTERED!");
                $duplicateCount++;
            } else {
                $this->line("{$rec->MaHoatDong} ({$rec->activity_type}) - Score: {$rec->recommendation_score} ✓");
            }
        }

        if ($duplicateCount > 0) {
            $this->error("\n⚠ FOUND $duplicateCount DUPLICATES! Recommendations pointing to already-registered activities");
            return 1;
        } else {
            $this->info("\n✓ No duplicates found - recommendations are CLEAN");
            return 0;
        }
    }
}
