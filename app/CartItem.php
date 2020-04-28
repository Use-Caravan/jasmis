<?php

namespace App;

use App\CModel;
use Common;
use DB;
use App;

class CartItem extends CModel
{    
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cart_item';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'cart_item_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'cart_item_key';

    /**
     * The attributes that table key generate
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
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



    /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }  
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }    
}
