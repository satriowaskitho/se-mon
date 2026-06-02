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
        static::saved(function ($report) {
            \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');
        });

        static::deleted(function ($report) {
            \Illuminate\Support\Facades\Cache::forget('kabupaten_stats');
        });
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class, 'assignment_id', 'id');
    }
}
