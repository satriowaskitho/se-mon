<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ClearTestProgress extends Command
{
    protected $signature = 'semon:clear-test-progress';
    protected $description = 'Clear all test progress data by truncating daily reports and clearing caches';

    public function handle()
    {
        $this->info("=== MEMULAI PEMBERSIHAN DATA PROGRESS TEST ===");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DailyReport::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Clear related caches
        Cache::forget('kabupaten_stats');
        Cache::forget('landing_stats');
        Cache::forget('map_progress');

        $this->info("Berhasil mengosongkan tabel daily_reports dan membersihkan cache.");
        $this->info("SEMON sekarang berada pada kondisi baseline kosong.");
        return 0;
    }
}
