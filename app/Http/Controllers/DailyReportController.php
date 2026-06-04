<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyReportRequest;
use App\Models\DailyReport;
use App\Models\Assignment;
use App\Repositories\DailyReportRepository;
use App\Repositories\AssignmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DailyReportController extends Controller
{
    protected DailyReportRepository $reportRepo;
    protected AssignmentRepository $assignmentRepo;

    public function __construct(DailyReportRepository $reportRepo, AssignmentRepository $assignmentRepo)
    {
        $this->reportRepo = $reportRepo;
        $this->assignmentRepo = $assignmentRepo;
    }

    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $reports = null;

        $isPcl = $user->role === 'pcl';
        $isPml = $user->role === 'pml';
        $isAdmin = $user->role === 'admin';

        if ($isPcl) {
            $pcl = $user->pcl;
            if (!$pcl) {
                abort(403, 'PCL profile not found.');
            }
            $reports = $this->reportRepo->getHistoryByPcl($pcl->id, 15);
        } elseif ($isPml) {
            $pml = $user->pml;
            if (!$pml) {
                abort(403, 'PML profile not found.');
            }
            $reports = $this->reportRepo->getHistoryByPmlPaginated($pml->id, 15);
        } elseif ($isAdmin) {
            $reports = $this->reportRepo->getHistoryForAdminPaginated(15);
        } else {
            abort(403, 'Unauthorized.');
        }

        return view('pcl.history', compact('reports', 'isPcl', 'isPml', 'isAdmin'));
    }

    /**
     * Show the form for creating a new daily report.
     */
    public function create(Request $request)
    {
        if ($request->user()->role !== 'pcl') {
            return redirect()->route('dashboard');
        }

        $pcl = $request->user()->pcl;
        if (!$pcl) {
            abort(403, 'PCL profile not found.');
        }

        // Get PCL's assigned SubSLS areas
        $assignments = $this->assignmentRepo->getByPcl($pcl->id);

        return view('pcl.input', compact('assignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDailyReportRequest $request)
    {
        $assignment = Assignment::findOrFail($request->assignment_id);

        // Authorization check: PCL can only report on their own assignments
        if ($request->user()->role === 'pcl') {
            $pcl = $request->user()->pcl;
            if (!$pcl || $assignment->pcl_id !== $pcl->id) {
                abort(403, 'Unauthorized to report for this SubSLS.');
            }
        }

        // Perform the UPSERT (updateOrCreate) operation
        $this->reportRepo->upsertReport($request->validated());

        return redirect()
            ->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $dailyReport)
    {
        // Use policy to authorize
        if (Gate::denies('update', $dailyReport)) {
            abort(403, 'Unauthorized.');
        }

        $assignments = collect([$dailyReport->assignment]);

        return view('pcl.edit', compact('dailyReport', 'assignments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreDailyReportRequest $request, DailyReport $dailyReport)
    {
        if (Gate::denies('update', $dailyReport)) {
            abort(403, 'Unauthorized.');
        }

        $this->reportRepo->upsertReport(array_merge($request->validated(), [
            'assignment_id' => $dailyReport->assignment_id,
            'report_date' => $dailyReport->report_date->format('Y-m-d'),
        ]));

        return redirect()
            ->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $dailyReport)
    {
        if (Gate::denies('delete', $dailyReport)) {
            abort(403, 'Unauthorized.');
        }

        $dailyReport->delete();

        return redirect()
            ->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil dihapus.');
    }
}
