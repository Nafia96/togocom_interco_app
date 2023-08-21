<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'agents';

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
	protected $fillable = ['diplome','date_naissance','zone','type_contrat','id_user','id_agence','is_delete'];

	public function admin()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}

    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function agence(){
        return $this->belongsTo(Agence::class,'id_agence');
    }
}
