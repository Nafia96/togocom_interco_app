<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOperateur extends Model
{
    protected $fillable = [
        'operateur_id',
        'email',
        'est_principal',
        'update_date'
    ];

    public function operateur()
    {
        return $this->belongsTo(Operateur::class);
    }
}

