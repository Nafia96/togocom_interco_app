<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPrice extends Model
{
    use HasFactory;

    protected $table = 'unit_prices';

    protected $fillable = [
        'direction', // e.g. TGT->TGC
        'period', // YYYY-MM
        'price', // decimal
        'effective_from', // datetime when set
        'created_by'
    ];
}
