<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contestation extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'contestation';

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
	protected $fillable = ['id_operator','id_invoice','amount','contesation_date',  'comment','operation_id' ];


	public function invoice(){
        return $this->belongsTo(Invoice::class,'id_invoice');
    }

	public function operator(){
        return $this->belongsTo(Operator::class,'id_operator');
    }



	/*
    public function latestContesation()
    {
        return $this->hasOne(Comptes::class)->latestOfMany();
    }



    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }

    public function user_add_by(){
        return $this->belongsTo(User::class,'add_by_id');
    }

$*/
}
