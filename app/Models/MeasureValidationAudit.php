<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasureValidationAudit extends Model
{
    use HasFactory;

    protected $table = 'measure_validation_audits';

    protected $fillable = [
        'measure_id',
        'changed_by',
        'old_value',
        'new_value',
        'comment',
    ];
}
