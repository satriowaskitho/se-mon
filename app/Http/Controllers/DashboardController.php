<?php

namespace App\Http\Controllers;

use App\Repositories\AssignmentRepository;
use App\Repositories\DailyReportRepository;
use App\Services\MonitoringService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected AssignmentRepository $assignmentRepo;
    protected DailyReportRepository $reportRepo;
    protected MonitoringService $monitoringService;

    public function __construct(
        AssignmentRepository $assignmentRepo,
        DailyReportRepository $reportRepo,
        MonitoringService $monitoringService
    ) {
        $this->assignmentRepo = $assignmentRepo;
        $this->reportRepo = $reportRepo;
        $this->monitoringService = $monitoringService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'pcl') {
            $pcl = $user->pcl;
            if (!$pcl) {
                abort(403, 'PCL profile not found for this account.');
            }

            // Get assignments for this PCL only, map computed progress fields
            $assignments = $this->assignmentRepo->getByPcl($pcl->id)->map(function ($a) {
                $a->usaha_realisasi = $a->dailyReports->sum('usaha_today');
                $a->ruta_realisasi  = $a->dailyReports->sum('ruta_today');
                $a->progress_pct    = $a->target_usaha > 0
                    ? ($a->usaha_realisasi / $a->target_usaha) * 100
                    : 0;
                return $a;
            });

            $stats = $this->monitoringService->getOverallStats($assignments);

            // Get recent history
            $recentReports = $this->reportRepo->getHistoryByPcl($pcl->id, 5);

            return view('pcl.dashboard', compact('stats', 'recentReports', 'assignments'));
        }

        // PML or Admin Flow
        // Fetch all assignments with full eager loading to avoid N+1 queries
        $assignments = $this->assignmentRepo->getAllWithRelations();
        $stats = $this->monitoringService->getOverallStats($assignments);

        return view('admin.dashboard', compact('stats'));
    }

    public function monitoring(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'pcl') {
            abort(403, 'Unauthorized. PCL tidak memiliki akses ke halaman detail monitoring.');
        }

        return view('admin.monitoring');
    }
}
