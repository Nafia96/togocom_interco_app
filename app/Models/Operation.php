<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operation';

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
	protected $fillable = ['operation_type','repayment_type','operation_name','comment','is_delete','id_invoice','id_op_account','add_by'
    ,'incoming_balance','output_balance','new_netting','amount','new_debt','new_receivable','account_number','id_operator','invoice_type'];



    public function operator(){
        return $this->belongsTo(Operator::class,'id_operator');
    }


    public function invoice(){
        return $this->belongsTo(Invoice::class,'id_invoice');
    }
/*
    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }

    public function user_add_by(){
        return $this->belongsTo(User::class,'add_by');
    }
    */

}
