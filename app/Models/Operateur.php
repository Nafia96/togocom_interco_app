<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    protected $fillable = ['nom', 'facture_path'];

    // Tous les emails
    public function emails()
    {
        return $this->hasMany(EmailOperateur::class);
    }

    // Email principal
    public function emailPrincipal()
    {
        return $this->hasOne(EmailOperateur::class)
                    ->where('est_principal', true);
    }

    // Emails en copie (CC)
    public function emailsCc()
    {
        return $this->hasMany(EmailOperateur::class)
                    ->where('est_principal', false);
    }

    /**
     * Retourne l'adresse email principale sous forme de string.
     */
    public function getEmailPrincipalAddress()
    {
        return optional($this->emailPrincipal)->email;
    }

    /**
     * Retourne toutes les adresses CC sous forme de tableau.
     */
    public function getEmailsCcList()
    {
        return $this->emailsCc->pluck('email')->toArray();
    }
}
