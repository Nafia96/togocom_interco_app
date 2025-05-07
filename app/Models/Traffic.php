<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traffic extends Model
{
    use HasFactory;

    protected $table = 'traffic';

    protected $fillable = [
        'direction',
        'period',
        'overall_total_records',
        'overall_total_gross',
        'overall_total_no_imsi',
        'total_records',
        'total_gross',
        'moc_voice_records',
        'moc_voice_duration',
        'moc_voice_gross',
        'mtc_gross',
        'moc_sms_records',
        'moc_sms_gross',
        'mtc_records',
        'mtc_duration',
        'gprs_records',
        'gprs_volume',
        'gprs_gross',
        'volte_records',
        'volte_volume',
        'volte_gross',
    ];
}
