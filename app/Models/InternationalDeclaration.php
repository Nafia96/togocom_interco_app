<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternationalDeclaration extends Model
{
    protected $fillable = [
        'period',
        'retail_tgc',
        'wholesale',
        'visitor_roaming',
        'charge',
    ];
}
