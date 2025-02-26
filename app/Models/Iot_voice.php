<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iot_voice extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'iot_voice2';

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

    protected $fillable = ["pays", "operateur", "code_operateur", "country_code", "mcc_mnc", "reseau_du_reseau", "mobile_autre_reseau", "mobile_filaire", "vers_cedeao", "vers_rest_afrique", "vers_rest_europe_amerique", "reste_monde", "satellite", "origine", "special_io", "free_roaming", "iot_standard"];


   /*

    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }

    public function user_add_by(){
        return $this->belongsTo(User::class,'add_by_id');
    }

$*/
}
