<?php

namespace App\Livewire;

use App\Repositories\AssignmentRepository;
use App\Services\MonitoringService;
use App\Exports\MonitoringExport;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\District;
use App\Models\Village;
use App\Models\Sls;
use App\Models\SubSls;
use App\Models\Pcl;
use App\Models\Pml;

class MonitoringDashboard extends Component
{
    use WithPagination;

    // Progress Monitoring Filters (Section 1)
    public $monitoringLevel = 'kec';
    public $dateFilter = '';
    public $kecFilter = '';
    public $desaFilter = '';
    public $slsFilter = '';
    public $pclFilter = '';
    public $pmlFilter = '';
    public $keywordFilter = '';

    // Daily Trend Analysis Filters (Section 2 - Independent)
    public $trendEntityType = 'kab';
    public $trendEntityId = '';
    public $trendStartDate = '';
    public $trendEndDate = '';

    // Drill down states
    public $drillKecId = '';
    public $drillDesaId = '';
    public $drillSlsId = '';

    // Lists for dropdown filters
    public $districtsList = [];
    public $villagesList = [];
    public $slsList = [];
    public $pclList = [];
    public $pmlList = [];

    // Livewire Query String for bookmarkable state
    protected $queryString = [
        'monitoringLevel' => ['except' => 'kec'],
        'dateFilter' => ['except' => ''],
        'kecFilter' => ['except' => ''],
        'desaFilter' => ['except' => ''],
        'slsFilter' => ['except' => ''],
        'pclFilter' => ['except' => ''],
        'pmlFilter' => ['except' => ''],
        'keywordFilter' => ['except' => ''],
        'drillKecId' => ['except' => ''],
        'drillDesaId' => ['except' => ''],
        'drillSlsId' => ['except' => ''],
        'trendEntityType' => ['except' => 'kab'],
        'trendEntityId' => ['except' => ''],
        'trendStartDate' => ['except' => ''],
        'trendEndDate' => ['except' => ''],
    ];

    public function mount()
    {
        $this->districtsList = District::all();
        $this->pclList = Pcl::orderBy('nama')->get();
        $this->pmlList = Pml::orderBy('nama')->get();
        $this->updateVillagesList();
        $this->updateSlsList();

        // Default Daily Trend Analysis State: Kabupaten Bintan, Last 14 Days
        $this->trendStartDate = Carbon::now()->subDays(13)->format('Y-m-d');
        $this->trendEndDate = Carbon::now()->format('Y-m-d');
    }

    public function updatedKecFilter()
    {
        $this->desaFilter = '';
        $this->slsFilter = '';
        $this->updateVillagesList();
        $this->updateSlsList();
        $this->resetPage();
    }

    public function updatedDesaFilter()
    {
        $this->slsFilter = '';
        $this->updateSlsList();
        $this->resetPage();
    }

    public function updatedTrendEntityType($value)
    {
        $this->trendEntityId = '';
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['dateFilter', 'slsFilter', 'pclFilter', 'pmlFilter', 'keywordFilter'])) {
            $this->resetPage();
        }
    }

    protected function updateVillagesList()
    {
        if ($this->kecFilter) {
            $this->villagesList = Village::where('idkec', $this->kecFilter)->orderBy('nmdesa')->get();
        } else {
            $this->villagesList = collect();
        }
    }

    protected function updateSlsList()
    {
        if ($this->desaFilter) {
            $this->slsList = Sls::where('iddesa', $this->desaFilter)->orderBy('nmsls')->get();
        } else {
            $this->slsList = collect();
        }
    }

    // Dynamic Select options for Daily Trend Analysis
    public function getTrendSelectorOptionsProperty()
    {
        if ($this->trendEntityType === 'kec') {
            return District::orderBy('nmkec')->get()->map(fn($d) => ['id' => $d->idkec, 'name' => $d->nmkec]);
        } elseif ($this->trendEntityType === 'desa') {
            return Village::orderBy('nmdesa')->get()->map(fn($v) => ['id' => $v->iddesa, 'name' => $v->nmdesa]);
        } elseif ($this->trendEntityType === 'subsls') {
            return SubSls::orderBy('idsubsls')->get()->map(fn($s) => ['id' => $s->idsubsls, 'name' => 'SubSLS ' . $s->kdsubsls . ' (' . $s->idsubsls . ')']);
        } elseif ($this->trendEntityType === 'pcl') {
            return Pcl::orderBy('nama')->get()->map(fn($p) => ['id' => $p->id, 'name' => $p->nama]);
        } elseif ($this->trendEntityType === 'pml') {
            return Pml::orderBy('nama')->get()->map(fn($p) => ['id' => $p->id, 'name' => $p->nama]);
        }
        return collect();
    }

    // Drill Down handlers
    public function selectDrillKec($idkec)
    {
        $this->drillKecId = $idkec;
        $this->drillDesaId = '';
        $this->drillSlsId = '';
    }

    public function selectDrillDesa($iddesa)
    {
        $this->drillDesaId = $iddesa;
        $this->drillSlsId = '';
    }

    public function selectDrillSls($idsls)
    {
        $this->drillSlsId = $idsls;
    }

    public function resetDrillKec()
    {
        $this->drillKecId = '';
        $this->drillDesaId = '';
        $this->drillSlsId = '';
    }

    public function resetDrillDesa()
    {
        $this->drillDesaId = '';
        $this->drillSlsId = '';
    }

    public function resetDrillSls()
    {
        $this->drillSlsId = '';
    }

    /**
     * Get processed & filtered assignments collection.
     */
    protected function getFilteredAssignments(AssignmentRepository $assignmentRepo)
    {
        $assignments = $assignmentRepo->getAllWithRelations();

        // 1. Apply Filters
        if ($this->kecFilter) {
            $assignments = $assignments->filter(fn($a) => $a->subsls->sls->village->idkec === $this->kecFilter);
        }
        if ($this->desaFilter) {
            $assignments = $assignments->filter(fn($a) => $a->subsls->sls->village->iddesa === $this->desaFilter);
        }
        if ($this->slsFilter) {
            $assignments = $assignments->filter(fn($a) => $a->subsls->idsls === $this->slsFilter);
        }
        if ($this->pclFilter) {
            $assignments = $assignments->filter(fn($a) => (int)$a->pcl_id === (int)$this->pclFilter);
        }
        if ($this->pmlFilter) {
            $assignments = $assignments->filter(fn($a) => (int)$a->pml_id === (int)$this->pmlFilter);
        }
        if ($this->keywordFilter) {
            $kw = strtolower($this->keywordFilter);
            $assignments = $assignments->filter(function($a) use ($kw) {
                return str_contains(strtolower($a->subsls->idsubsls), $kw) ||
                       str_contains(strtolower($a->subsls->sls->nmsls), $kw) ||
                       str_contains(strtolower($a->subsls->sls->village->nmdesa), $kw) ||
                       str_contains(strtolower($a->pcl->nama), $kw) ||
                       str_contains(strtolower($a->pml->nama), $kw);
            });
        }

        // 2. Map report values based on dateFilter
        $assignments = $assignments->map(function ($a) {
            $reports = $a->dailyReports;
            if ($this->dateFilter) {
                $reports = $reports->filter(function ($r) {
                    return Carbon::parse($r->report_date)->format('Y-m-d') === $this->dateFilter;
                });
            }

            // Bind values in-memory to prevent database N+1 recalculations
            $a->usaha_realisasi = $reports->sum('usaha_today');
            $a->ruta_realisasi = $reports->sum('ruta_today');
            $a->progress_pct = $a->target_usaha > 0 ? ($a->usaha_realisasi / $a->target_usaha) * 100 : 0;
            return $a;
        });

        return $assignments;
    }

    /**
     * Export table data to Excel.
     */
    public function exportExcel(AssignmentRepository $assignmentRepo)
    {
        $data = $this->getExportData($assignmentRepo);
        return Excel::download(new MonitoringExport($data), 'monitoring_se2026_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Export table data to CSV.
     */
    public function exportCsv(AssignmentRepository $assignmentRepo)
    {
        $data = $this->getExportData($assignmentRepo);
        return Excel::download(new MonitoringExport($data), 'monitoring_se2026_' . now()->format('Ymd_His') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    protected function getExportData(AssignmentRepository $assignmentRepo): array
    {
        $assignments = $this->getFilteredAssignments($assignmentRepo);
        $export = [];
        $i = 1;

        foreach ($assignments as $a) {
            $status = $a->progress_pct < 50 ? 'Merah' : ($a->progress_pct < 80 ? 'Kuning' : 'Hijau');
            $export[] = [
                $i++,
                $a->subsls->idsubsls,
                $a->subsls->sls->village->district->nmkec ?? 'N/A',
                $a->subsls->sls->village->nmdesa ?? 'N/A',
                $a->subsls->sls->nmsls ?? 'N/A',
                $a->pcl->nama ?? 'N/A',
                $a->pml->nama ?? 'N/A',
                $a->target_usaha,
                $a->usaha_realisasi,
                $a->ruta_realisasi,
                round($a->progress_pct, 2),
                $status,
            ];
        }

        return $export;
    }

    protected function getTrendTimelineData()
    {
        // 1. Resolve assignments for selected trend scope globally (ignores Section 1 filtering as instructed)
        $trendAssignments = \App\Models\Assignment::with(['subsls.sls.village.district', 'dailyReports', 'pcl', 'pml'])->get();

        if ($this->trendEntityType === 'kec' && $this->trendEntityId) {
            $trendAssignments = $trendAssignments->filter(fn($a) => $a->subsls->sls->village->idkec === $this->trendEntityId);
        } elseif ($this->trendEntityType === 'desa' && $this->trendEntityId) {
            $trendAssignments = $trendAssignments->filter(fn($a) => $a->subsls->sls->village->iddesa === $this->trendEntityId);
        } elseif ($this->trendEntityType === 'subsls' && $this->trendEntityId) {
            $trendAssignments = $trendAssignments->filter(fn($a) => $a->idsubsls === $this->trendEntityId);
        } elseif ($this->trendEntityType === 'pcl' && $this->trendEntityId) {
            $trendAssignments = $trendAssignments->filter(fn($a) => (int)$a->pcl_id === (int)$this->trendEntityId);
        } elseif ($this->trendEntityType === 'pml' && $this->trendEntityId) {
            $trendAssignments = $trendAssignments->filter(fn($a) => (int)$a->pml_id === (int)$this->trendEntityId);
        }

        // 2. Fetch daily reports and group by report_date
        $dailyReports = $trendAssignments->flatMap(fn($a) => $a->dailyReports);

        // Filter by date range
        if ($this->trendStartDate) {
            $dailyReports = $dailyReports->filter(fn($r) => Carbon::parse($r->report_date)->format('Y-m-d') >= $this->trendStartDate);
        }
        if ($this->trendEndDate) {
            $dailyReports = $dailyReports->filter(fn($r) => Carbon::parse($r->report_date)->format('Y-m-d') <= $this->trendEndDate);
        }

        $dailyData = $dailyReports->groupBy(function ($report) {
            return Carbon::parse($report->report_date)->format('Y-m-d');
        })
        ->map(function ($reports, $date) {
            return [
                'date' => $date,
                'usaha_today' => $reports->sum('usaha_today'),
                'ruta_today' => $reports->sum('ruta_today'),
            ];
        })
        ->sortBy('date');

        // Fill in dates that have 0 entries to avoid empty categories/timeline breaks
        $categories = [];
        $usahaSeries = [];
        $rutaSeries = [];

        $start = Carbon::parse($this->trendStartDate ?: Carbon::now()->subDays(13)->format('Y-m-d'));
        $end = Carbon::parse($this->trendEndDate ?: Carbon::now()->format('Y-m-d'));

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $categories[] = $dateStr;

            $dayData = $dailyData->get($dateStr);
            $usahaSeries[] = $dayData ? $dayData['usaha_today'] : 0;
            $rutaSeries[] = $dayData ? $dayData['ruta_today'] : 0;
        }

        return [
            'categories' => $categories,
            'usaha_series' => $usahaSeries,
            'ruta_series' => $rutaSeries,
        ];
    }

    public function render(AssignmentRepository $assignmentRepo, MonitoringService $monitoringService)
    {
        // 1. Get filtered assignments (Section 1 scope)
        $filtered = $this->getFilteredAssignments($assignmentRepo);

        // 2. Overall Stats
        $stats = $monitoringService->getOverallStats($filtered);

        // 3. Dynamic Progress aggregation based on selected level
        $chartProgress = collect();
        if ($this->monitoringLevel === 'kec') {
            $chartProgress = $monitoringService->getProgressByKecamatan($filtered);
        } elseif ($this->monitoringLevel === 'desa') {
            $chartProgress = $monitoringService->getProgressByDesa($filtered);
        } elseif ($this->monitoringLevel === 'subsls') {
            $chartProgress = $monitoringService->getProgressBySubsls($filtered);
        } elseif ($this->monitoringLevel === 'pcl') {
            $chartProgress = $monitoringService->getProgressByPcl($filtered);
        } elseif ($this->monitoringLevel === 'pml') {
            $chartProgress = $monitoringService->getProgressByPml($filtered);
        }

        // 4. Calculate Top 10 Best and Lowest Progress
        $topProgress = $chartProgress->sortByDesc('percentage')->take(10)->values();
        $lowestProgress = $chartProgress->sortBy('percentage')->take(10)->values();

        // 5. In-Memory Calculation for "PCL Perlu Perhatian" Alert Widget
        $pclGroups = $filtered->groupBy('pcl_id');
        $attentionPcls = [];

        foreach ($pclGroups as $pclId => $group) {
            $pclName = $group->first()->pcl->nama ?? 'N/A';
            
            // Calculate progress safely handling target = 0/null/missing penugasan
            $target = $group->sum('target_usaha');
            $realisasi = $group->sum('usaha_realisasi');
            $pct = $target > 0 ? ($realisasi / $target) * 100 : 0;

            // Find last activity date
            $reports = $group->flatMap(fn($a) => $a->dailyReports);
            $latestReport = $reports->sortByDesc('report_date')->first();
            $lastActivity = $latestReport ? Carbon::parse($latestReport->report_date)->format('Y-m-d') : null;

            // Check alert criteria (progress < 25% or no activity in 3 days)
            $needsAttention = false;
            $reason = '';
            
            if ($pct < 25) {
                $needsAttention = true;
                $reason = 'Progres di bawah 25%';
            } elseif (!$lastActivity || Carbon::parse($lastActivity)->lt(Carbon::now()->subDays(3))) {
                $needsAttention = true;
                $reason = 'Tidak ada aktivitas > 3 hari';
            }

            if ($needsAttention) {
                $attentionPcls[] = [
                    'name' => $pclName,
                    'progress' => round($pct, 2),
                    'last_activity' => $lastActivity ?? 'Belum ada',
                    'reason' => $reason
                ];
            }
        }

        // Sort by progress ascending so worst-performing PCLs show first
        $attentionPcls = collect($attentionPcls)->sortBy('progress')->values();

        // 6. Drill down calculation
        $drillData = collect();
        $drillLevel = 'kab';

        if ($this->drillKecId && !$this->drillDesaId) {
            $drillLevel = 'desa';
            $villages = Village::where('idkec', $this->drillKecId)->get();
            $drillData = $villages->map(function ($v) use ($filtered) {
                $subFiltered = $filtered->filter(fn($a) => $a->subsls->sls->iddesa === $v->iddesa);
                $target = $subFiltered->sum('target_usaha');
                $realisasi = $subFiltered->sum('usaha_realisasi');
                $pct = $target > 0 ? ($realisasi / $target) * 100 : 0;
                return [
                    'id' => $v->iddesa,
                    'name' => $v->nmdesa,
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'percentage' => round($pct, 2),
                    'color' => $this->getProgressColor($pct),
                ];
            });
        } elseif ($this->drillKecId && $this->drillDesaId && !$this->drillSlsId) {
            $drillLevel = 'sls';
            $slss = Sls::where('iddesa', $this->drillDesaId)->get();
            $drillData = $slss->map(function ($s) use ($filtered) {
                $subFiltered = $filtered->filter(fn($a) => $a->subsls->idsls === $s->idsls);
                $target = $subFiltered->sum('target_usaha');
                $realisasi = $subFiltered->sum('usaha_realisasi');
                $pct = $target > 0 ? ($realisasi / $target) * 100 : 0;
                return [
                    'id' => $s->idsls,
                    'name' => $s->nmsls,
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'percentage' => round($pct, 2),
                    'color' => $this->getProgressColor($pct),
                ];
            });
        } elseif ($this->drillKecId && $this->drillDesaId && $this->drillSlsId) {
            $drillLevel = 'subsls';
            $subFiltered = $filtered->filter(fn($a) => $a->subsls->idsls === $this->drillSlsId);
            $drillData = $subFiltered->map(function ($a) {
                return [
                    'id' => $a->subsls->idsubsls,
                    'name' => 'SubSLS ' . $a->subsls->kdsubsls . ' (PCL: ' . ($a->pcl->nama ?? '-') . ')',
                    'target' => $a->target_usaha,
                    'realisasi' => $a->usaha_realisasi,
                    'percentage' => round($a->progress_pct, 2),
                    'color' => $this->getProgressColor($a->progress_pct),
                ];
            });
        } else {
            $drillLevel = 'kec';
            $districts = District::all();
            $drillData = $districts->map(function ($d) use ($filtered) {
                $subFiltered = $filtered->filter(fn($a) => $a->subsls->sls->village->idkec === $d->idkec);
                $target = $subFiltered->sum('target_usaha');
                $realisasi = $subFiltered->sum('usaha_realisasi');
                $pct = $target > 0 ? ($realisasi / $target) * 100 : 0;
                return [
                    'id' => $d->idkec,
                    'name' => $d->nmkec,
                    'target' => $target,
                    'realisasi' => $realisasi,
                    'percentage' => round($pct, 2),
                    'color' => $this->getProgressColor($pct),
                ];
            });
        }

        // 7. Paginated monitoring table list
        $perPage = 10;
        $currentPage = $this->getPage();
        $currentPageItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($currentPageItems, $filtered->count(), $perPage, $currentPage, [
            'path' => url()->current(),
        ]);

        // 8. Independent daily timeline calculations for Section 2
        $trendTimeline = $this->getTrendTimelineData();

        $levelLabel = $this->monitoringLevel === 'kec' ? 'Kecamatan' : ($this->monitoringLevel === 'desa' ? 'Desa' : ($this->monitoringLevel === 'subsls' ? 'SubSLS' : ($this->monitoringLevel === 'pcl' ? 'PCL' : 'PML')));

        // Dispatch updated chart options to frontend JS
        $this->dispatch('chart-data-updated', [
            'kecCategories' => $chartProgress->take(10)->pluck('name')->toArray(),
            'kecSeries' => $chartProgress->take(10)->pluck('percentage')->toArray(),
            'kecTargets' => $chartProgress->take(10)->pluck('target')->toArray(),
            'kecRealisasis' => $chartProgress->take(10)->pluck('realisasi')->toArray(),
            'levelLabel' => $levelLabel,
            'timelineCategories' => $trendTimeline['categories'],
            'timelineUsaha' => $trendTimeline['usaha_series'],
            'timelineRuta' => $trendTimeline['ruta_series'],
        ]);

        return view('livewire.monitoring-dashboard', [
            'stats' => $stats,
            'chartProgress' => $chartProgress->take(10), // Limit chart visual display to 10 for readability
            'topProgress' => $topProgress,
            'lowestProgress' => $lowestProgress,
            'attentionPcls' => $attentionPcls,
            'drillData' => $drillData,
            'drillLevel' => $drillLevel,
            'drillBreadcrumbs' => $this->getDrillBreadcrumbs(),
            'tableData' => $paginated,
            'trendTimeline' => $trendTimeline,
        ]);
    }

    protected function getDrillBreadcrumbs(): array
    {
        $crumbs = [['label' => 'Kecamatan', 'action' => 'resetDrillKec']];
        if ($this->drillKecId) {
            $kecName = District::where('idkec', $this->drillKecId)->first()->nmkec ?? 'N/A';
            $crumbs[] = ['label' => $kecName, 'action' => 'resetDrillDesa'];
        }
        if ($this->drillDesaId) {
            $desaName = Village::where('iddesa', $this->drillDesaId)->first()->nmdesa ?? 'N/A';
            $crumbs[] = ['label' => $desaName, 'action' => 'resetDrillSls'];
        }
        if ($this->drillSlsId) {
            $slsName = Sls::where('idsls', $this->drillSlsId)->first()->nmsls ?? 'N/A';
            $crumbs[] = ['label' => $slsName, 'action' => ''];
        }
        return $crumbs;
    }

    protected function getProgressColor(float $percentage): string
    {
        if ($percentage < 50) {
            return 'red';
        } elseif ($percentage < 80) {
            return 'yellow';
        } else {
            return 'green';
        }
    }
}
