<?php

namespace App;

use Common;

class CorporateVoucher extends CModel
{
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'corporate_voucher';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'corporate_voucher_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corporate_voucher_key',
        'voucher_number',
        'order_id',
        'user_corporate_id',
    ];

    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'corporate_voucher_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;
    

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

    public static function getCorporateVoucher($corporateVoucherKey) 
    {
        $corporateVoucher = CorporateVoucher::select(CorporateVoucherItem::tableName().'.*')
                           ->leftjoin(CorporateVoucherItem::tableName(),CorporateVoucher::tableName().'.corporate_voucher_id',CorporateVoucherItem::tableName().'.corporate_voucher_id')
                            ->where([CorporateVoucher::tableName().'.corporate_voucher_key' => $corporateVoucherKey,
                                    CorporateVoucherItem::tableName().'.is_claimed' => 1
                            ]);
        return $corporateVoucher;
    }
}
