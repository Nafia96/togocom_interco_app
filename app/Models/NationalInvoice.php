<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NationalInvoice extends Model
{
    use HasFactory;

    protected $table = 'national_invoices';

    protected $fillable = [
        'invoice_number', 'direction', 'period', 'periodDate', 'invoice_date',
        'total_volume', 'total_valorisation', 'total_ttc', 'lines_json', 'created_by', 'facture_name', 'comment'
    ];
}
