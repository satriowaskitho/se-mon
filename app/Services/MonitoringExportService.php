<?php

namespace App\Services;

use App\Exports\MonitoringExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class MonitoringExportService
{
    /**
     * Export the filtered assignments query to an Excel file response.
     */
    public function exportExcel(Builder $query)
    {
        $data = $this->getExportData($query);
        return Excel::download(new MonitoringExport($data), 'monitoring_se2026_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Export the filtered assignments query to a CSV file response.
     */
    public function exportCsv(Builder $query)
    {
        $data = $this->getExportData($query);
        return Excel::download(new MonitoringExport($data), 'monitoring_se2026_' . now()->format('Ymd_His') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Map the database records to the export array structure.
     */
    protected function getExportData(Builder $query): array
    {
        // Get all matching records (without limits or pagination)
        $assignments = $query->get();
        $export = [];
        $i = 1;

        foreach ($assignments as $a) {
            $pct = (float) $a->progress_pct;
            
            // Map progress status classification
            if ($pct < 25) {
                $status = 'Perlu Perhatian';
            } elseif ($pct < 50) {
                $status = 'Rendah';
            } elseif ($pct < 80) {
                $status = 'Waspada';
            } else {
                $status = 'Baik';
            }

            $export[] = [
                $i++,
                $a->idsubsls,
                $a->subsls->sls->village->district->nmkec ?? 'N/A',
                $a->subsls->sls->village->nmdesa ?? 'N/A',
                $a->subsls->sls->nmsls ?? 'N/A',
                $a->pcl->nama ?? 'N/A',
                $a->pml->nama ?? 'N/A',
                (int) $a->target_usaha,
                (int) $a->usaha_realisasi,
                (int) $a->ruta_realisasi,
                round($pct, 2),
                $status,
            ];
        }

        return $export;
    }
}
