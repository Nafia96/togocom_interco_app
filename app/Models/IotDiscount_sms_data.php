<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IotDiscount_sms_data extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'iot_end_user2';

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

    protected $fillable = ['pays','operateur','code_operateur','for_one_sms,for_bundle', 'iot_standard', 'created_at', 'updated_at'];


    public function operator(){
        return $this->belongsTo(Operator::class,'id_operator');
    }

   /*

    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }

    public function user_add_by(){
        return $this->belongsTo(User::class,'add_by_id');
    }

$*/
}
