<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\DailyReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TestProgressSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            $this->command->error("TestProgressSeeder can only be run in local environment.");
            return;
        }

        $this->command->info("=== RUNNING DETERMINISTIC TEST PROGRESS SEEDER ===");

        // Fetch 5 assignments with target_usaha >= 25 to be realistic
        $assignments = Assignment::where('target_usaha', '>=', 25)->limit(5)->get();
        if ($assignments->count() < 5) {
            $assignments = Assignment::limit(5)->get();
        }

        if ($assignments->count() === 0) {
            $this->command->error("No assignments found. Please seed the database baseline first.");
            return;
        }

        // Truncate previous daily reports to ensure deterministic progress
        DailyReport::truncate();

        // 5 reports, totaling exactly 100 usaha and 125 ruta
        // 5 reports, each with 20 usaha, 25 ruta
        for ($i = 0; $i < 5; $i++) {
            $assignment = $assignments[$i % $assignments->count()];
            $reportDate = Carbon::now()->subDays(5 - $i)->format('Y-m-d');

            DailyReport::create([
                'assignment_id' => $assignment->id,
                'report_date' => $reportDate,
                'usaha_today' => 20,
                'ruta_today' => 25,
                'notes' => 'Laporan uji coba deterministik.',
            ]);
        }

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');
        \Illuminate\Support\Facades\Cache::forget('landing_stats');
        \Illuminate\Support\Facades\Cache::forget('map_progress');

        $this->command->info("Successfully seeded exactly 5 daily reports (Total: 100 Usaha, 125 Ruta).");
    }
}
