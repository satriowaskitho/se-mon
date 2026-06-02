<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $primaryKey = 'idkec';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idkec',
        'kdkec',
        'nmkec',
        'idkab',
        'kdkab',
        'nmkab',
    ];

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'idkec', 'idkec');
    }
}
