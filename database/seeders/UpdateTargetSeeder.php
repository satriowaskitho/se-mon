<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UpdateTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/files/20260608 Rekap Prelist_21.xlsx');
        if (!file_exists($file)) {
            $file = 'D:\\2026\\10. Sensus Ekonomi\\20260608 Rekap Prelist_21.xlsx';
        }

        if (!file_exists($file)) {
            $this->command->error("Excel file not found at local or repo path.");
            return;
        }

        $this->command->info("Loading Excel file: {$file}...");
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $this->command->info("Updating assignments target from row 3 to {$highestRow}...");
        $updatedCount = 0;
        $totalNewTarget = 0;

        for ($row = 3; $row <= $highestRow; $row++) {
            $idsubsls = trim($sheet->getCellByColumnAndRow(4, $row)->getValue() ?? ''); // Column D (IDSUBSLS_25_2)
            $target = (int)$sheet->getCellByColumnAndRow(30, $row)->getValue(); // Column AD (TOTAL ASSIGNMENT FASIH)

            if (empty($idsubsls)) {
                continue;
            }

            // Update assignment
            $assignment = Assignment::where('idsubsls', $idsubsls)->first();
            if ($assignment) {
                $assignment->update([
                    'target_usaha' => $target
                ]);
                $updatedCount++;
                $totalNewTarget += $target;
            } else {
                $this->command->warn("Assignment for idsubsls '{$idsubsls}' not found in DB.");
            }
        }

        // Clear dashboard caching keys
        Cache::forget('landing_stats');
        Cache::forget('kabupaten_stats');
        Cache::forget('map_progress');

        $this->command->info("Successfully updated {$updatedCount} assignments.");
        $this->command->info("New sum of target_usaha: " . number_format($totalNewTarget, 0, ',', '.'));
    }
}
