<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resum extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'Resum';

	/**
	* The primary key for the model.
	*
	* @var string
	*/
	protected $primaryKey = 'id';


	/**
	* The attributes that are mass assignable.
	*
	* @var arrayclient
	*/
	protected $fillable = ['id_operator','period','receivable','debt' ,'incoming_payement','payout','netting','id_invoice_1','id_invoice_2','service'];

    /*
    public function latestResum()
    {
        return $this->hasOne(Comptes::class)->latestOfMany();
    }

    public function client(){
        return $this->belongsTo(Client::class,'id_client');
    }

    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }

    public function user_add_by(){
        return $this->belongsTo(User::class,'add_by_id');
    }

$*/
}
