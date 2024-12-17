<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'journals';

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
	protected $fillable = ['action','user_id'];

	public function admin()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}
}
