<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubSls extends Model
{
    protected $table = 'subsls';
    protected $primaryKey = 'idsubsls';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idsubsls',
        'idsls',
        'kdsubsls',
    ];

    public function sls(): BelongsTo
    {
        return $this->belongsTo(Sls::class, 'idsls', 'idsls');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'idsubsls', 'idsubsls');
    }
}
