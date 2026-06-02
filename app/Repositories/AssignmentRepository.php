<?php

namespace App\Repositories;

use App\Models\Assignment;
use Illuminate\Database\Eloquent\Collection;

class AssignmentRepository
{
    /**
     * Get all assignments with fully eager-loaded relations to avoid N+1 query issues.
     */
    public function getAllWithRelations(): Collection
    {
        return Assignment::with([
            'pcl',
            'pml',
            'subsls.sls.village.district',
            'dailyReports'
        ])->get();
    }

    /**
     * Get assignments assigned to a specific PCL with eager-loaded relations.
     */
    public function getByPcl(int $pclId): Collection
    {
        return Assignment::where('pcl_id', $pclId)
            ->with([
                'pcl',
                'pml',
                'subsls.sls.village.district',
                'dailyReports'
            ])
            ->get();
    }

    /**
     * Find assignment by ID.
     */
    public function find(int $id): ?Assignment
    {
        return Assignment::with([
            'pcl',
            'pml',
            'subsls.sls.village.district',
            'dailyReports'
        ])->find($id);
    }
}
