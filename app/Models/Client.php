<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'client';

	/**
	* The primary key for the model.
	*
	* @var string
	*/
	protected $primaryKey = 'id';


	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = ['occupation','type_client','adresse_collecte','tel_b','id_user','id_agence','is_delete','id_agent','type_of_add_by'];


    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }


    public function operations(){
        return $this->hasMany(Operation::class,'id_client');
    }

}
