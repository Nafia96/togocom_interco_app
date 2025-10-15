<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measure extends Model
{
    use HasFactory;

    protected $table = 'measures';

    protected $fillable = [
        'period',
        'm_tgt',
        'm_tgc',
        'diff',
        'pct_diff',
        'traffic_validated',
        'valuation',
        'comment',
        'direction',
        'created_by'
    ];
}
