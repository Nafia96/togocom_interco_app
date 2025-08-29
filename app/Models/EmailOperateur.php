<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOperateur extends Model
{
        protected $table = 'emails_operateurs'; // 👈 correction ici

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

