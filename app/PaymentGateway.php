<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;

class PaymentGateway extends CModel
{    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payment_gateway';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'payment_gateway_id';
        

    /**
     * Table key generate variable 
     * 
     * @var string
     */
    protected $keyGenerate = false;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token'];     
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    	    
}
