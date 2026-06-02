<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pml;
use App\Models\Pcl;
use App\Models\Assignment;
use App\Models\DailyReport;
use App\Models\SubSls;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Imports\SemonDataImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Initial Excel Geographical & Operational Import
        $excelPath = base_path('SE2026_Normalized_Seeder_StringFixed.xlsx');
        if (file_exists($excelPath)) {
            $this->command->info("=== MEMULAI IMPORT GEOGRAFIS & OPERASIONAL DARI EXCEL ===");
            $import = new SemonDataImport($excelPath);
            $import->import();
            $this->command->info("Excel Import completed successfully!");
        } else {
            $this->command->error("Excel seeder file not found at: {$excelPath}");
            return;
        }

        // 2. Clear Daily Reports & recreate Admin account
        $this->command->info("\n=== MEMBERSIHKAN DATA HARIAN & MEMBUAT AKUN ADMIN ===");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DailyReport::truncate();
        User::where('email', 'admin@semon.id')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Create Admin User
        User::create([
            'name' => 'Admin SEMON',
            'email' => 'admin@semon.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        $this->command->info("Admin User created successfully: admin@semon.id / admin123");

        // 4. Generate simulated Daily Reports for all 751 Assignments!
        $this->command->info("=== MEMULAI SIMULASI PROGRESS HARIAN UNTUK 751 ASSIGNMENTS ===");
        
        $assignments = Assignment::all();
        $totalAssignments = $assignments->count();
        $this->command->info("Ditemukan {$totalAssignments} penugasan untuk disimulasikan.");

        $dates = [];
        for ($d = 0; $d < 14; $d++) {
            $dates[] = Carbon::now()->subDays(13 - $d)->format('Y-m-d');
        }

        $chunks = $assignments->chunk(100);
        $totalCreated = 0;

        foreach ($chunks as $chunk) {
            foreach ($chunk as $assignment) {
                // Determine a progress profile for this assignment
                $rand = rand(1, 100);
                
                if ($rand <= 15) {
                    // 15% probability: Not started (0% progress)
                    continue;
                } elseif ($rand <= 25) {
                    // 10% probability: Low progress (5% - 24%)
                    $targetPct = rand(5, 24);
                } elseif ($rand <= 55) {
                    // 30% probability: Waspada progress (50% - 79%)
                    $targetPct = rand(50, 79);
                } else {
                    // 45% probability: Good/Baik progress (80% - 100%)
                    $targetPct = rand(80, 100);
                }

                $targetUsaha = $assignment->target_usaha;
                if ($targetUsaha <= 0) {
                    continue;
                }

                $totalRealisasi = (int)round($targetUsaha * $targetPct / 100);
                if ($totalRealisasi <= 0) {
                    continue;
                }

                // Distribute $totalRealisasi across 2 to 4 active days randomly chosen from the last 14 days
                $numActiveDays = min(rand(2, 4), count($dates));
                $activeDays = (array)array_rand($dates, $numActiveDays);
                if (!is_array($activeDays)) {
                    $activeDays = [$activeDays];
                }

                $remainingUsaha = $totalRealisasi;
                foreach ($activeDays as $idx => $dayKey) {
                    $reportDate = $dates[$dayKey];
                    
                    if ($idx === count($activeDays) - 1) {
                        $usahaToday = $remainingUsaha;
                    } else {
                        $usahaToday = (int)round($totalRealisasi / count($activeDays)) + rand(-1, 1);
                        $usahaToday = max(1, min($usahaToday, $remainingUsaha));
                    }
                    $remainingUsaha -= $usahaToday;

                    if ($usahaToday > 0) {
                        $rutaToday = (int)round($usahaToday * 1.25) + rand(-1, 1);
                        $rutaToday = max(0, $rutaToday);

                        DailyReport::create([
                            'assignment_id' => $assignment->id,
                            'report_date' => $reportDate,
                            'usaha_today' => $usahaToday,
                            'ruta_today' => $rutaToday,
                            'notes' => 'Pencacahan harian.',
                            'created_at' => Carbon::parse($reportDate)->setHour(rand(8, 17))->setMinute(rand(0, 59)),
                            'updated_at' => Carbon::parse($reportDate)->setHour(rand(8, 17))->setMinute(rand(0, 59)),
                        ]);
                        $totalCreated++;
                    }

                    if ($remainingUsaha <= 0) {
                        break;
                    }
                }
            }
        }

        // Clean cache after seeding
        \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');

        $this->command->info("Berhasil membuat {$totalCreated} laporan harian simulasi untuk {$totalAssignments} penugasan.");
        $this->command->info("\n=== SEEDER SELESAI DIJALANKAN DENGAN SUKSES ===");
    }
}
