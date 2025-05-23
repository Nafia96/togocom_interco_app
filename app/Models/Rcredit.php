<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rcredit extends Model
{
    use HasFactory;


     /**
	* The table associated with the model.
	*
	* @var string
	*/
	protected $table = 'rcredit';

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
	protected $fillable = ['amount','date'];
}
