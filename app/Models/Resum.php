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
	protected $table = 'resum';

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
	protected $fillable = ['incoming_payement_cfa','payout_cfa','receivable_cfa','debt_cfa','id_operator','period','periodDate','receivable','debt' ,'incoming_payement','payout','netting','id_operation_1','id_operation_2','service',];


	public function operator(){
        return $this->belongsTo(Operator::class,'id_operator');
    }

	public function operation1(){
        return $this->belongsTo(Operation::class,'id_operation_1');
    }

	public function operation2(){
        return $this->belongsTo(Operation::class,'id_operation_2');
    }



	/*
    public function latestResum()
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
