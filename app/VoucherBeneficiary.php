<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\LanguageScope;

class VoucherBeneficiary extends CModel
{
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'voucher_beneficiary';
    
    /**
	 * The database table primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'voucher_beneficiary_id';

    /**
	 * The database table translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "voucher_id";
       

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table fillable column names
     *
     * @var array
     */
    protected $fillable = ['voucher_id','beneficiary_type','beneficiary_id']; 
    
    public static function existsShopBeneficiary($voucherId)
    {
        $beneficiaryName = self::select('beneficiary_id')->where(['voucher_id' => $voucherId, 'beneficiary_type' => PROMO_FOR_ALL_SHOPS])->get()->toArray();
        return array_column( $beneficiaryName,'beneficiary_id');
    }
    public static function existsUserBeneficiary($voucherId)
    {
        $beneficiaryName = self::select('beneficiary_id')->where(['voucher_id' => $voucherId, 'beneficiary_type' => PROMO_FOR_ALL_USERS])->get()->toArray();
        return array_column( $beneficiaryName,'beneficiary_id');
    }

}
