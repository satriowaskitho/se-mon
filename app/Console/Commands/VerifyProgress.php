<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Assignment;
use App\Models\District;
use App\Models\Pcl;
use App\Models\Pml;
use Illuminate\Support\Carbon;

class VerifyProgress extends Command
{
    protected $signature = 'semon:verify-progress';
    protected $description = 'Audit and verify hierarchical target and realisasi progress aggregation rules';

    public function handle()
    {
        $this->info("=== MEMULAI AUDIT KONSISTENSI HIERARKIS PROGRESS SE2026 ===");

        // Fetch assignments with relationships and daily reports
        $assignments = Assignment::with(['subsls.sls.village.district', 'dailyReports', 'pcl', 'pml'])->get();

        if ($assignments->isEmpty()) {
            $this->error("Tidak ditemukan data assignment di database. Jalankan seeder terlebih dahulu!");
            return 1;
        }

        // 1. CALCULATE INDEPENDENT LEVEL SUMS
        $kabupatenTarget = $assignments->sum('target_usaha');
        $kabupatenRealisasi = $assignments->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today');

        // Group by Kecamatan
        $kecGroups = $assignments->groupBy('subsls.sls.village.idkec');
        $sumKecTarget = $kecGroups->map(fn($g) => $g->sum('target_usaha'))->sum();
        $sumKecRealisasi = $kecGroups->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();

        // Group by Desa
        $desaGroups = $assignments->groupBy('subsls.sls.iddesa');
        $sumDesaTarget = $desaGroups->map(fn($g) => $g->sum('target_usaha'))->sum();
        $sumDesaRealisasi = $desaGroups->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();

        // Group by SLS
        $slsGroups = $assignments->groupBy('subsls.idsls');
        $sumSlsTarget = $slsGroups->map(fn($g) => $g->sum('target_usaha'))->sum();
        $sumSlsRealisasi = $slsGroups->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();

        // Group by SubSLS
        $subslsGroups = $assignments->groupBy('idsubsls');
        $sumSubslsTarget = $subslsGroups->map(fn($g) => $g->sum('target_usaha'))->sum();
        $sumSubslsRealisasi = $subslsGroups->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();

        $this->info("\n--- A. VERIFIKASI HIERARKI TARGET ---");
        $this->checkEquality("SUM(SubSLS Target) = SLS Target", $sumSubslsTarget, $sumSlsTarget);
        $this->checkEquality("SUM(SLS Target) = Desa Target", $sumSlsTarget, $sumDesaTarget);
        $this->checkEquality("SUM(Desa Target) = Kecamatan Target", $sumDesaTarget, $sumKecTarget);
        $this->checkEquality("SUM(Kecamatan Target) = Kabupaten Target", $sumKecTarget, $kabupatenTarget);

        $this->info("\n--- B. VERIFIKASI HIERARKI REALISASI ---");
        $this->checkEquality("SUM(SubSLS Realisasi) = SLS Realisasi", $sumSubslsRealisasi, $sumSlsRealisasi);
        $this->checkEquality("SUM(SLS Realisasi) = Desa Realisasi", $sumSlsRealisasi, $sumDesaRealisasi);
        $this->checkEquality("SUM(Desa Realisasi) = Kecamatan Realisasi", $sumDesaRealisasi, $sumKecRealisasi);
        $this->checkEquality("SUM(Kecamatan Realisasi) = Kabupaten Realisasi", $sumKecRealisasi, $kabupatenRealisasi);

        // 2. PML & PCL HIERARCHY VALIDATION (Based on ownership)
        $this->info("\n--- C. VERIFIKASI HIERARKI OPERASIONAL (OWNERSHIP) ---");
        
        $pclSumTarget = $assignments->groupBy('pcl_id')->map(fn($g) => $g->sum('target_usaha'))->sum();
        $pmlSumTarget = $assignments->groupBy('pml_id')->map(fn($g) => $g->sum('target_usaha'))->sum();
        
        $this->checkEquality("SUM(PCL Target via Assignments) = Kabupaten Target", $pclSumTarget, $kabupatenTarget);
        $this->checkEquality("SUM(PML Target via Assignments) = Kabupaten Target", $pmlSumTarget, $kabupatenTarget);

        $pclSumRealisasi = $assignments->groupBy('pcl_id')->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();
        $pmlSumRealisasi = $assignments->groupBy('pml_id')->map(fn($g) => $g->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today'))->sum();

        $this->checkEquality("SUM(PCL Realisasi via Assignments) = Kabupaten Realisasi", $pclSumRealisasi, $kabupatenRealisasi);
        $this->checkEquality("SUM(PML Realisasi via Assignments) = Kabupaten Realisasi", $pmlSumRealisasi, $kabupatenRealisasi);

        // 3. SUMMARY OUTPUT
        $kabProgress = $kabupatenTarget > 0 ? ($kabupatenRealisasi / $kabupatenTarget) * 100 : 0;
        
        $this->info("\n========================================================");
        $this->info("=== RINGKASAN PROGRESS SE2026 KABUPATEN BINTAN ===");
        $this->info("========================================================");
        $this->line(sprintf("Total Target Kabupaten    : %s", number_format($kabupatenTarget)));
        $this->line(sprintf("Total Realisasi Kabupaten : %s", number_format($kabupatenRealisasi)));
        $this->line(sprintf("Progress Kabupaten        : %s%%", number_format($kabProgress, 2)));
        $this->info("========================================================");

        // Progress per Kecamatan
        $this->info("\n--- PROGRESS PER KECAMATAN ---");
        $kecTable = [];
        foreach ($kecGroups as $idkec => $group) {
            $name = $group->first()->subsls->sls->village->district->nmkec ?? 'N/A';
            $t = $group->sum('target_usaha');
            $r = $group->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today');
            $p = $t > 0 ? ($r / $t) * 100 : 0;
            $kecTable[] = [
                'Kecamatan' => $name,
                'Target' => number_format($t),
                'Realisasi' => number_format($r),
                'Progress' => sprintf("%.2f%%", $p)
            ];
        }
        $this->table(['Kecamatan', 'Target', 'Realisasi', 'Progress'], $kecTable);

        // Progress per PML
        $this->info("\n--- PROGRESS PER PML ---");
        $pmlTable = [];
        foreach ($assignments->groupBy('pml_id') as $pmlId => $group) {
            $name = $group->first()->pml->nama ?? 'N/A';
            $t = $group->sum('target_usaha');
            $r = $group->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today');
            $p = $t > 0 ? ($r / $t) * 100 : 0;
            $pmlTable[] = [
                'PML Name' => $name,
                'Target' => number_format($t),
                'Realisasi' => number_format($r),
                'Progress' => sprintf("%.2f%%", $p)
            ];
        }
        $this->table(['PML Name', 'Target', 'Realisasi', 'Progress'], $pmlTable);

        // Progress per PCL
        $this->info("\n--- PROGRESS PER PCL ---");
        $pclTable = [];
        foreach ($assignments->groupBy('pcl_id') as $pclId => $group) {
            $name = $group->first()->pcl->nama ?? 'N/A';
            $t = $group->sum('target_usaha');
            $r = $group->flatMap(fn($a) => $a->dailyReports)->sum('usaha_today');
            $p = $t > 0 ? ($r / $t) * 100 : 0;
            $pclTable[] = [
                'PCL Name' => $name,
                'Target' => number_format($t),
                'Realisasi' => number_format($r),
                'Progress' => sprintf("%.2f%%", $p)
            ];
        }
        $this->table(['PCL Name', 'Target', 'Realisasi', 'Progress'], $pclTable);

        return 0;
    }

    private function checkEquality(string $label, float $val1, float $val2)
    {
        $diff = abs($val1 - $val2);
        // Using low threshold to support floating-point comparison
        if ($diff < 0.0001) {
            $this->line(sprintf("  [PASS] %s (Value: %s)", $label, number_format($val1)));
        } else {
            $this->error(sprintf("  [FAIL] %s (Values: %s vs %s)", $label, number_format($val1), number_format($val2)));
        }
    }
}
