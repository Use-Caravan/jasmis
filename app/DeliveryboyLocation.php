<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Common;
use DB;
use App;

class DeliveryboyLocation extends CModel
{
    /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'deliveryboy_location';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'deliveryboy_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'deliveryboy_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    //protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    //protected $guarded = ['latitude','longitude','csrf-token'];    
    
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
        //echo self::where(self::uniqueKey(), $key)->first()->toSql();exit;
        return self::where(self::uniqueKey(), $key)->first();
    }

}
