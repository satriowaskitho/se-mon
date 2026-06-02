<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sls extends Model
{
    protected $table = 'sls';
    protected $primaryKey = 'idsls';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idsls',
        'iddesa',
        'kdsls',
        'nmsls',
    ];

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class, 'iddesa', 'iddesa');
    }

    public function subsls(): HasMany
    {
        return $this->hasMany(SubSls::class, 'idsls', 'idsls');
    }
}
