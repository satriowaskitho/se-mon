<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'report_date',
        'assignment_id',
        'usaha_today',
        'ruta_today',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    protected static function booted()
    {
        $clearCache = function () {
            \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');
            \Illuminate\Support\Facades\Cache::forget('landing_stats');
            \Illuminate\Support\Facades\Cache::forget('map_progress');
        };

        static::saved($clearCache);
        static::deleted($clearCache);
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}
