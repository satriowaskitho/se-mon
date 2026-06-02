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
        // 1. Initial Excel Geographical Import
        $excelPath = base_path('SE2026_Normalized_Seeder_StringFixed.xlsx');
        if (file_exists($excelPath)) {
            $this->command->info("=== MEMULAI IMPORT GEOGRAFIS DARI EXCEL ===");
            $import = new SemonDataImport($excelPath);
            $import->import();
            $this->command->info("Excel Import completed successfully!");
        } else {
            $this->command->error("Excel seeder file not found at: {$excelPath}");
            return;
        }

        // 2. Clear Operational Tables to prevent duplicate or conflicting entries
        $this->command->info("\n=== MEMBERSIHKAN DAN MEREDESAIN DATA OPERASIONAL ===");
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DailyReport::truncate();
        Assignment::truncate();
        Pcl::truncate();
        Pml::truncate();
        User::whereIn('role', ['pcl', 'pml'])->delete();
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

        // 4. Create Exactly 3 PMLs
        $pmlsData = [
            ['id' => 101, 'nama' => 'Purnomo PML', 'username' => 'purnomopml'],
            ['id' => 102, 'nama' => 'Qonita PML', 'username' => 'qonitapml'],
            ['id' => 103, 'nama' => 'Rian PML', 'username' => 'rianpml'],
        ];

        $pmls = [];
        foreach ($pmlsData as $pmlData) {
            $user = User::create([
                'name' => $pmlData['nama'],
                'email' => $pmlData['username'] . '@semon.id',
                'password' => Hash::make($pmlData['username'] . '123'),
                'role' => 'pml',
            ]);

            $pml = Pml::create([
                'id' => $pmlData['id'],
                'nama' => $pmlData['nama'],
                'user_id' => $user->id,
            ]);

            $pmls[] = $pml;
        }
        $this->command->info("3 PML Users created successfully.");

        // 5. Create Exactly 8 PCLs with targeted performance levels
        $pclsData = [
            // High Performers (80% - 95%)
            ['id' => 201, 'nama' => 'Apri Palwamin', 'username' => 'apripalwamin', 'pml_index' => 0, 'target_pct' => 92],
            ['id' => 202, 'nama' => 'Budi PCL', 'username' => 'budipcl', 'pml_index' => 0, 'target_pct' => 85],
            // Medium Performers (40% - 70%)
            ['id' => 203, 'nama' => 'Cici PCL', 'username' => 'cicipcl', 'pml_index' => 0, 'target_pct' => 65],
            ['id' => 204, 'nama' => 'Dedi PCL', 'username' => 'dedipcl', 'pml_index' => 1, 'target_pct' => 55],
            ['id' => 205, 'nama' => 'Elga PCL', 'username' => 'elgapcl', 'pml_index' => 1, 'target_pct' => 48],
            ['id' => 206, 'nama' => 'Fani PCL', 'username' => 'fanipcl', 'pml_index' => 1, 'target_pct' => 42],
            // Low Performers (5% - 30%)
            ['id' => 207, 'nama' => 'Gita PCL', 'username' => 'gitapcl', 'pml_index' => 2, 'target_pct' => 12],
            ['id' => 208, 'nama' => 'Hadi PCL', 'username' => 'hadipcl', 'pml_index' => 2, 'target_pct' => 8],
        ];

        // Fetch shuffled SubSLS to assign uniquely
        $availableSubSls = SubSls::all()->shuffle();
        if ($availableSubSls->count() < 32) {
            $this->command->error("SubSLS count is less than 32 in database. Seeding cannot proceed safely.");
            return;
        }

        $assignmentIndex = 0;
        $this->command->info("=== MEMULAI GENERASI LAPORAN HARIAN 14 HARI TERAKHIR ===");

        // Dates loop: last 14 days
        $dates = [];
        for ($d = 0; $d < 14; $d++) {
            $dates[] = Carbon::now()->subDays(13 - $d);
        }

        foreach ($pclsData as $pclData) {
            $user = User::create([
                'name' => $pclData['nama'],
                'email' => $pclData['username'] . '@semon.id',
                'password' => Hash::make($pclData['username'] . '123'),
                'role' => 'pcl',
            ]);

            $pcl = Pcl::create([
                'id' => $pclData['id'],
                'nama' => $pclData['nama'],
                'user_id' => $user->id,
            ]);

            // Assign exactly 4 SubSLS to this PCL
            $pclAssignments = [];
            $totalTargetUsaha = 0;

            for ($i = 0; $i < 4; $i++) {
                $subSls = $availableSubSls[$assignmentIndex++];
                $targetUsaha = rand(60, 110);
                
                $assignment = Assignment::create([
                    'idsubsls' => $subSls->idsubsls,
                    'pcl_id' => $pcl->id,
                    'pml_id' => $pmls[$pclData['pml_index']]->id,
                    'target_usaha' => $targetUsaha,
                ]);

                $pclAssignments[] = $assignment;
                $totalTargetUsaha += $targetUsaha;
            }

            // Calculate precise target realisasi based on performer target_pct
            $targetRealisasiUsaha = (int)round($totalTargetUsaha * $pclData['target_pct'] / 100);

            // Determine day profiles for the 14 days
            $dayProfiles = [];
            $activeDaysIndices = [];
            foreach ($dates as $idx => $date) {
                if ($date->isSunday()) {
                    $dayProfiles[$idx] = 'zero'; // Rest day
                } else {
                    $dayProfiles[$idx] = 'normal';
                    $activeDaysIndices[] = $idx;
                }
            }

            // Add one extra random zero activity day
            if (!empty($activeDaysIndices)) {
                $extraZeroIdx = array_rand($activeDaysIndices);
                $dayProfiles[$activeDaysIndices[$extraZeroIdx]] = 'zero';
                unset($activeDaysIndices[$extraZeroIdx]);
                $activeDaysIndices = array_values($activeDaysIndices);
            }

            // Designate 2 Low Activity days
            for ($k = 0; $k < 2; $k++) {
                if (!empty($activeDaysIndices)) {
                    $lowIdx = array_rand($activeDaysIndices);
                    $dayProfiles[$activeDaysIndices[$lowIdx]] = 'low';
                    unset($activeDaysIndices[$lowIdx]);
                    $activeDaysIndices = array_values($activeDaysIndices);
                }
            }

            // Designate 3 High Activity days
            for ($k = 0; $k < 3; $k++) {
                if (!empty($activeDaysIndices)) {
                    $highIdx = array_rand($activeDaysIndices);
                    $dayProfiles[$activeDaysIndices[$highIdx]] = 'high';
                    unset($activeDaysIndices[$highIdx]);
                    $activeDaysIndices = array_values($activeDaysIndices);
                }
            }

            // Assign weights to day profiles
            $weights = [];
            $totalWeight = 0;
            foreach ($dayProfiles as $idx => $profile) {
                if ($profile === 'zero') $w = 0.0;
                elseif ($profile === 'low') $w = 0.35;
                elseif ($profile === 'high') $w = 1.75;
                else $w = 1.0;

                $weights[$idx] = $w;
                $totalWeight += $w;
            }

            // Calculate realisasi distribution
            $dailyRealisasi = [];
            $allocatedRealisasi = 0;
            $baseUnit = $totalWeight > 0 ? $targetRealisasiUsaha / $totalWeight : 0;

            foreach ($weights as $idx => $w) {
                if ($w == 0.0) {
                    $dailyRealisasi[$idx] = 0;
                } else {
                    $val = (int)round($w * $baseUnit);
                    $dailyRealisasi[$idx] = $val;
                    $allocatedRealisasi += $val;
                }
            }

            // Distribute discrepancy to guarantee exact final realisasi sum
            $diff = $targetRealisasiUsaha - $allocatedRealisasi;
            if ($diff != 0) {
                // Find first non-zero day to adjust
                foreach ($dailyRealisasi as $idx => $val) {
                    if ($val > 0) {
                        $dailyRealisasi[$idx] = max(0, $val + $diff);
                        break;
                    }
                }
            }

            // Create Daily Reports for each day
            foreach ($dates as $idx => $date) {
                $reportUsaha = $dailyRealisasi[$idx];
                if ($reportUsaha === 0) continue;

                // Distribute this day's progress across PCL's assignments
                $remainingUsaha = $reportUsaha;
                foreach ($pclAssignments as $assIdx => $assignment) {
                    // For the last assignment, dump remaining usaha
                    if ($assIdx === count($pclAssignments) - 1) {
                        $allocatedUsaha = $remainingUsaha;
                    } else {
                        $allocatedUsaha = (int)round($reportUsaha / count($pclAssignments));
                        $allocatedUsaha = min($allocatedUsaha, $remainingUsaha);
                    }
                    $remainingUsaha -= $allocatedUsaha;

                    if ($allocatedUsaha > 0) {
                        $rutaToday = (int)round($allocatedUsaha * 1.25) + rand(-2, 2);
                        $rutaToday = max(0, $rutaToday);

                        // Simulating delayed reporting: 10% entered one day later
                        $reportDateStr = $date->format('Y-m-d');
                        $isDelayed = (rand(1, 100) <= 10);
                        if ($isDelayed) {
                            $createdDate = Carbon::parse($reportDateStr)->addDay()->setHour(rand(8, 17))->setMinute(rand(0, 59));
                        } else {
                            $createdDate = Carbon::parse($reportDateStr)->setHour(rand(8, 17))->setMinute(rand(0, 59));
                        }

                        DailyReport::create([
                            'assignment_id' => $assignment->id,
                            'report_date' => $reportDateStr,
                            'usaha_today' => $allocatedUsaha,
                            'ruta_today' => $rutaToday,
                            'notes' => $this->getRandomNotes($pclData['nama'], $reportUsaha),
                            'created_at' => $createdDate,
                            'updated_at' => $createdDate,
                        ]);
                    }
                }
            }

            $this->command->info(sprintf("  PCL: %s (%s) seeded. Total Target: %d, Seeding Realisasi: %d (Progress: %d%%)",
                $pclData['nama'],
                $pclData['username'],
                $totalTargetUsaha,
                $targetRealisasiUsaha,
                $pclData['target_pct']
            ));
        }

        $this->command->info("\n=== SEEDER SELESAI DIJALANKAN DENGAN SUKSES ===");
    }

    private function getRandomNotes(string $pclName, int $progress): string
    {
        $notes = [
            "Pencacahan berjalan lancar, cuaca sangat mendukung.",
            "Semua target hari ini selesai diwawancarai.",
            "Dijumpai beberapa usaha perdagangan baru di wilayah ini.",
            "Ada kendala hujan gerimis di siang hari, namun target tetap terkejar.",
            "Sebagian ruta sedang tidak di rumah, dilanjutkan sore hari.",
            "Pencacahan didampingi oleh ketua RT setempat.",
        ];

        if ($progress < 5) {
            return "Aktivitas minim, hanya re-visit wilayah.";
        }
        return $notes[array_rand($notes)];
    }
}
