<?php

namespace App;

use Common;

class CorporateVoucherItem extends CModel
{
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'corporate_voucher_item';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'corporate_voucher_item_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corporate_voucher_id',
        'order_item_id',
        'quantity',
        'is_claimed',
        'claimed_at',
    ];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
