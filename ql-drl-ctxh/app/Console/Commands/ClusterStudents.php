<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ActivityRecommendationService;
use Illuminate\Support\Facades\Log;

class ClusterStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cluster:generate {--force : Bỏ qua xác nhận}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tính toán và tạo đề xuất hoạt động cho sinh viên dựa trên thuật toán clustering';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Bắt đầu tính toán đề xuất hoạt động...');
        $this->newLine();

        try {
            $service = new ActivityRecommendationService();
            $service->generateRecommendations();

            $this->info('✅ Tính toán xong!');
            Log::info('Clustering command executed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Lỗi: ' . $e->getMessage());
            Log::error('Clustering command error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}