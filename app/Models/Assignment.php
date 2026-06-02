<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'idsubsls',
        'pcl_id',
        'pml_id',
        'target_usaha',
    ];

    public function subsls(): BelongsTo
    {
        return $this->belongsTo(SubSls::class, 'idsubsls', 'idsubsls');
    }

    public function pcl(): BelongsTo
    {
        return $this->belongsTo(Pcl::class, 'pcl_id', 'id');
    }

    public function pml(): BelongsTo
    {
        return $this->belongsTo(Pml::class, 'pml_id', 'id');
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class, 'assignment_id', 'id');
    }
}
