<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\LanguageScope;
use App\Branch;
use DB;

class Voucher extends CModel
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
	protected $table = 'voucher';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'voucher_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'voucher_key';

    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['shopbeneficiary_id','userbeneficiary_id','csrf-token'];    

    

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
        $query = self::select($self->getTable().".*");
        return $query;
	}
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{  
		return self::where(self::uniqueKey(),$key)->first();
      
    }

    public function selectApplyPromo($value = null)
    {
        $applyPromo = [ 
            //VOUCHER_APPLY_PROMO_SHOPS => __('admincrud.Shops'), 
            VOUCHER_APPLY_PROMO_USERS => __('admincrud.Users'), 
            //PROMO_FOR_BOTH => __('admincrud.Both') 
        ];
        if($value != null) {
            if($value == PROMO_FOR_BOTH) {
                return $applyPromo[VOUCHER_APPLY_PROMO_SHOPS].', '.$applyPromo[VOUCHER_APPLY_PROMO_USERS];
            }
            return $applyPromo[$value];
        }
        return $applyPromo;
    }

    /**
     * @param string $value should be string by comma operator 
     * 1,2,3
     */
    public function selectAppTypes($values = null)
    {
        $appTypes = [ 
            APP_TYPE_WEB => __('admincrud.Web'),  
            APP_TYPE_ANDROID => __('admincrud.Android'), 
            APP_TYPE_IOS => __('admincrud.IOS'), 
            APP_TYPE_WINDOWS => __('admincrud.Windows') 
        ];
        if($values != null) {            
            $data  = explode(',',$values);            
            $appType = '';
            foreach ($data as $key => $value) {
                $appType .= (isset($appTypes[$value])) ? ((count($data) == $key+1) ? $appTypes[$value] : $appTypes[$value].', ' ) : '';
            }            
            return $appType;
        }
        return $appTypes;
    }
    public function selectPromoForShops()
    {
        $promoShops = [
            PROMO_SHOPS_ALL => __('admincommon.All'),  
            PROMO_SHOPS_PARTICULAR => __('admincommon.Particular')
        ];
        return $promoShops;
    }
    public function selectPromoForUser()
    {
        $promoUser = [
            PROMO_USER_ALL => __('admincommon.All'),  
            PROMO_USER_PARTICULAR => __('admincommon.Particular')
        ];
        return $promoUser;
    }

    



}
