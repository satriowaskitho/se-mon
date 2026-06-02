<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\District;
use App\Models\Village;
use App\Models\Pcl;
use App\Models\Pml;
use App\Services\MonitoringExportService;
use Livewire\Component;
use Livewire\WithPagination;

class MonitoringDetail extends Component
{
    use WithPagination;

    // Filters & Search
    public $search = '';
    public $kecFilter = '';
    public $desaFilter = '';
    public $pmlFilter = '';
    public $pclFilter = '';
    public $statusFilter = '';

    // Sorting (Default: Progress ASC, lowest performing first)
    public $sortField = 'progress_pct';
    public $sortDirection = 'asc';

    // Dropdown Lists
    public $districtsList = [];
    public $villagesList = [];
    public $pmlList = [];
    public $pclList = [];

    public function mount()
    {
        $this->districtsList = District::orderBy('nmkec')->get();
        $this->pmlList = Pml::orderBy('nama')->get();
        $this->pclList = Pcl::orderBy('nama')->get();
        $this->updateVillagesList();
    }

    public function updatedKecFilter()
    {
        $this->desaFilter = '';
        $this->updateVillagesList();
        $this->resetPage();
    }

    public function updatedDesaFilter()
    {
        $this->resetPage();
    }

    public function updatedPmlFilter()
    {
        $this->resetPage();
    }

    public function updatedPclFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function updateVillagesList()
    {
        if ($this->kecFilter) {
            $this->villagesList = Village::where('idkec', $this->kecFilter)->orderBy('nmdesa')->get();
        } else {
            $this->villagesList = collect();
        }
    }

    /**
     * Interactive column sorting handler.
     */
    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Build the optimized aggregate database query.
     */
    protected function buildQuery()
    {
        $pctExpression = 'CASE WHEN target_usaha > 0 THEN ((SELECT COALESCE(SUM(usaha_today), 0) FROM daily_reports WHERE daily_reports.assignment_id = assignments.id) * 100.0 / target_usaha) ELSE 0 END';

        $query = Assignment::query()
            ->select('assignments.*')
            ->selectRaw('(SELECT COALESCE(SUM(usaha_today), 0) FROM daily_reports WHERE daily_reports.assignment_id = assignments.id) as usaha_realisasi')
            ->selectRaw('(SELECT COALESCE(SUM(ruta_today), 0) FROM daily_reports WHERE daily_reports.assignment_id = assignments.id) as ruta_realisasi')
            ->selectRaw('(SELECT MAX(report_date) FROM daily_reports WHERE daily_reports.assignment_id = assignments.id) as last_report_date')
            ->selectRaw($pctExpression . ' as progress_pct')
            ->join('subsls', 'assignments.idsubsls', '=', 'subsls.idsubsls')
            ->join('sls', 'subsls.idsls', '=', 'sls.idsls')
            ->join('villages', 'sls.iddesa', '=', 'villages.iddesa')
            ->join('districts', 'villages.idkec', '=', 'districts.idkec')
            ->join('pcls', 'assignments.pcl_id', '=', 'pcls.id')
            ->join('pmls', 'assignments.pml_id', '=', 'pmls.id')
            ->with(['pcl', 'pml', 'subsls.sls.village.district']); // Eager load for Blade display

        // Apply filters
        if ($this->pmlFilter) {
            $query->where('assignments.pml_id', $this->pmlFilter);
        }

        if ($this->pclFilter) {
            $query->where('assignments.pcl_id', $this->pclFilter);
        }

        if ($this->kecFilter) {
            $query->where('villages.idkec', $this->kecFilter);
        }

        if ($this->desaFilter) {
            $query->where('sls.iddesa', $this->desaFilter);
        }

        if ($this->statusFilter) {
            if ($this->statusFilter === 'perlu_perhatian') {
                $query->whereRaw("{$pctExpression} < 25");
            } elseif ($this->statusFilter === 'rendah') {
                $query->whereRaw("{$pctExpression} >= 25 AND {$pctExpression} < 50");
            } elseif ($this->statusFilter === 'waspada') {
                $query->whereRaw("{$pctExpression} >= 50 AND {$pctExpression} < 80");
            } elseif ($this->statusFilter === 'baik') {
                $query->whereRaw("{$pctExpression} >= 80");
            }
        }

        // Apply search keyword
        if ($this->search) {
            $kw = '%' . $this->search . '%';
            $query->where(function ($q) use ($kw) {
                $q->where('assignments.idsubsls', 'like', $kw)
                  ->orWhere('sls.nmsls', 'like', $kw)
                  ->orWhere('villages.nmdesa', 'like', $kw)
                  ->orWhere('districts.nmkec', 'like', $kw)
                  ->orWhere('pcls.nama', 'like', $kw)
                  ->orWhere('pmls.nama', 'like', $kw);
            });
        }

        // Apply sorting
        $sortableFields = [
            'idsubsls'        => 'assignments.idsubsls',
            'kecamatan'        => 'districts.nmkec',
            'desa'             => 'villages.nmdesa',
            'sls'              => 'sls.nmsls',
            'pcl'              => 'pcls.nama',
            'pml'              => 'pmls.nama',
            'target_usaha'     => 'assignments.target_usaha',
            'usaha_realisasi'  => 'usaha_realisasi',
            'ruta_realisasi'   => 'ruta_realisasi',
            'progress_pct'     => 'progress_pct'
        ];

        $orderCol = $sortableFields[$this->sortField] ?? 'progress_pct';
        $query->orderBy($orderCol, $this->sortDirection);

        return $query;
    }

    /**
     * Download data to Excel.
     */
    public function exportExcel(MonitoringExportService $exportService)
    {
        return $exportService->exportExcel($this->buildQuery());
    }

    /**
     * Download data to CSV.
     */
    public function exportCsv(MonitoringExportService $exportService)
    {
        return $exportService->exportCsv($this->buildQuery());
    }

    public function render()
    {
        $paginated = $this->buildQuery()->paginate(15);

        return view('livewire.monitoring-detail', [
            'tableData' => $paginated,
        ]);
    }
}
