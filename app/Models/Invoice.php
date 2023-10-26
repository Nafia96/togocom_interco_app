<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'invoice';

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
	protected $fillable = ['invoice_number','period','invoice_date','number_of_call'
    ,'call_volume','invoice_type','add_by','tgc_invoice','amount','comment','invoice_type','operator_id','periodDate'];


    public function operator()
    {
        return $this->belongsTo(Operator::class,'operator_id');
    }

	public function contestation()
    {
		
		return $this->hasOne(Contestation::class, 'id_invoice');  
 	 }

	  public function creditnote()
	  {
		  
		  return $this->hasOne(Creditnote::class, 'id_invoice');  
	  }



   /*  public function client(){
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
