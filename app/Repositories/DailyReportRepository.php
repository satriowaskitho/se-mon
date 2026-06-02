<?php

namespace App\Repositories;

use App\Models\DailyReport;
use Carbon\Carbon;

class DailyReportRepository
{
    public function upsertReport(array $data): DailyReport
    {
        return DailyReport::updateOrCreate(
            [
                'report_date' => Carbon::parse($data['report_date'])->format('Y-m-d'),
                'assignment_id' => $data['assignment_id'],
            ],
            [
                'usaha_today' => $data['usaha_today'],
                'ruta_today' => $data['ruta_today'],
                'notes' => $data['notes'] ?? null,
            ]
        );
    }

    public function getByAssignmentAndDate(int $assignmentId, string $date)
    {
        return DailyReport::where('assignment_id', $assignmentId)
            ->whereDate('report_date', Carbon::parse($date)->format('Y-m-d'))
            ->first();
    }

    public function getHistoryByPcl(int $pclId, int $perPage = 15)
    {
        return DailyReport::whereHas('assignment', function ($q) use ($pclId) {
            $q->where('pcl_id', $pclId);
        })
        ->with(['assignment.subsls.sls.village.district'])
        ->orderBy('report_date', 'desc')
        ->paginate($perPage);
    }
}
