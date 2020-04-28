<?php

namespace App;

use App\CModel;
use Common;

class OTPTemp extends CModel
{    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'otp_temp';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'otp_temp_id';

     /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'otp_temp_key';
    
    /**
     * The attributes that enable unique key generation.
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
