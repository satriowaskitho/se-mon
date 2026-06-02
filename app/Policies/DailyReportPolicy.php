<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;

class DailyReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, DailyReport $dailyReport): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'pml') {
            $pml = $user->pml;
            return $pml && $dailyReport->assignment->pml_id === $pml->id;
        }

        if ($user->role === 'pcl') {
            $pcl = $user->pcl;
            return $pcl && $dailyReport->assignment->pcl_id === $pcl->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->role === 'pcl' || $user->role === 'admin';
    }

    public function update(User $user, DailyReport $dailyReport): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'pcl') {
            $pcl = $user->pcl;
            return $pcl && $dailyReport->assignment->pcl_id === $pcl->id;
        }

        return false;
    }

    public function delete(User $user, DailyReport $dailyReport): bool
    {
        return $user->role === 'admin';
    }
}
