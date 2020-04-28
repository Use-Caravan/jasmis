<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AddressTypeLang;
use Common;
use DB;
use App;
use Auth;


class UserLoyaltyCredit extends CModel
{
 
    use SoftDeletes;
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_loyalty_credit';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_loyalty_credit';

     /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'user_loyalty_credit_key';
    
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }    
    
       
}
