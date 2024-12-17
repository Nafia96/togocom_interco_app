<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compteTontine extends Model
{
    use HasFactory;

    protected $table = 'compte_tontine';

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
	protected $fillable = ['compte','debut_de_cotisation','account_number','description','solde_total'
    ,'solde_actuelle','taux_cotisation','beniefice_accumule'
    ,'nombre_de_retrait','id_client','id_agence','create_by','delete_by','reactive_by'];

	public function admin()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}

    public function user(){
        return $this->belongsTo(User::class,'id_chef_agence');
    }
}
