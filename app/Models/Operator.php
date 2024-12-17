<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;
    protected $table = 'operator';

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
	protected $fillable = ['is_carrier',
	'afrique','xaf_conversion','euro_conversion',
	'dollar_conversion','name','tel','tel2','email',
	'email2','email3','cedeao','adresse','country',
	'currency','description','is_delete','rib','ope_account_number','banque_adresse','swift_code','is_delete'];

    public function account()
    {
        return $this->hasOne(Account::class, 'id_operator');
    }


}
