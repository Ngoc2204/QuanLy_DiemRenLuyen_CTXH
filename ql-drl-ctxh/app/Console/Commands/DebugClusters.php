<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DebugClusters extends Command
{
    protected $signature = 'debug:clusters';
    protected $description = 'Debug cluster distribution and recommendations';

    public function handle()
    {
        // Get cluster distribution
        $clusters = DB::table('student_clusters')
            ->select('ClusterID', DB::raw('COUNT(*) as count'))
            ->groupBy('ClusterID')
            ->orderBy('ClusterID')
            ->get();

        $this->info('=== Cluster Distribution ===');
        foreach ($clusters as $c) {
            $this->line("Cluster $c->ClusterID: $c->count students");
        }

        // Get recommendation stats
        $recCount = DB::table('activity_recommendations')->count();
        $recStudents = DB::table('activity_recommendations')->distinct('MSSV')->count();
        
        $this->info("\n=== Recommendations ===");
        $this->line("Total recommendations: $recCount");
        $this->line("Students with recommendations: $recStudents");

        // Get participation
        $drlPart = DB::table('dangkyhoatdongdrl')
            ->where('TrangThaiThamGia', 'Đã tham gia')
            ->count();
        $ctxhPart = DB::table('dangkyhoatdongctxh')
            ->where('TrangThaiThamGia', 'Đã tham gia')
            ->count();

        $this->info("\n=== Participation ===");
        $this->line("DRL participations: $drlPart");
        $this->line("CTXH participations: $ctxhPart");

        // Check sample cluster members and their recommendations
        $this->info("\n=== Sample Analysis ===");
        $sample = DB::table('student_clusters')
            ->where('ClusterID', 0)
            ->limit(3)
            ->get();
        
        foreach ($sample as $s) {
            $recCount = DB::table('activity_recommendations')
                ->where('MSSV', $s->MSSV)
                ->count();
            $this->line("Student $s->MSSV (Cluster $s->ClusterID): $recCount recommendations");
        }

        // Debug query for cluster 0
        $this->info("\n=== Debug Query for Cluster 0 ===");
        $clusterZeroMembers = DB::table('student_clusters')
            ->where('ClusterID', 0)
            ->pluck('MSSV')
            ->toArray();
        
        $this->line("Cluster 0 members: " . implode(", ", $clusterZeroMembers));

        $activities = DB::table('dangkyhoatdongdrl as dk')
            ->join('hoatdongdrl as hd', 'dk.MaHoatDong', '=', 'hd.MaHoatDong')
            ->select('dk.MaHoatDong', 'hd.category_tags', DB::raw('COUNT(*) as popularity'))
            ->whereIn('dk.MSSV', $clusterZeroMembers)
            ->where('dk.TrangThaiThamGia', 'Đã tham gia')
            ->groupBy('dk.MaHoatDong', 'hd.category_tags')
            ->get();

        $this->line("Activities returned: " . count($activities));
        foreach ($activities as $act) {
            $this->line("  - Activity: $act->MaHoatDong, Popularity: $act->popularity");
        }

        // Check registrations for first student
        $firstStudent = $clusterZeroMembers[0];
        $this->info("\n=== Registrations for $firstStudent ===");
        $registered = DB::table('dangkyhoatdongdrl')
            ->where('MSSV', $firstStudent)
            ->pluck('MaHoatDong')
            ->toArray();
        $this->line("Already registered: " . implode(", ", $registered));

        // Filter activities not registered
        $notRegistered = [];
        foreach ($activities as $act) {
            if (!in_array($act->MaHoatDong, $registered)) {
                $notRegistered[] = $act->MaHoatDong;
            }
        }
        $this->line("Activities to recommend: " . implode(", ", $notRegistered));
    }
}
