<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    protected $primaryKey = 'iddesa';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'iddesa',
        'idkec',
        'kddesa',
        'nmdesa',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'idkec', 'idkec');
    }

    public function sls(): HasMany
    {
        return $this->hasMany(Sls::class, 'iddesa', 'iddesa');
    }
}
