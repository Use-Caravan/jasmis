<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AddressTypeLang;
use Common;
use DB;
use App;
use Auth;


class UserAddress extends CModel
{
 
    use SoftDeletes;
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_address';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_address_id';

     /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'user_address_key';
    
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
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        return $query;
	}
        
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{   
		return self::where(self::uniqueKey(), $key)->first();
    }

    public static function getAddressDetails()
    {
        $addressDetails = self::select(self::tableName().'.*')
                          ->leftjoin(AddressType::tableName(),self::tableName().'.address_type_id',AddressType::tableName().'.address_type_id')
                          ->where(self::tableName().'.user_id',Auth::guard(GUARD_USER)->user()->user_id);
                          AddressTypeLang::selectTranslation($addressDetails);
                          $addressDetails = $addressDetails->get();
                          return $addressDetails;
    } 

    public static function getAllAddress()
    {
        $address = self::getList()
                   ->addSelect(User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username',User::tableName().'.email')
                   ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id')
                   ->leftjoin(AddressType::tableName(),self::tableName().'.address_type_id',AddressType::tableName().'.address_type_id');
                   AddressTypeLang::selectTranslation($address);
        return $address;
                   
    } 

    
}
