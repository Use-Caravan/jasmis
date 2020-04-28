<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoucherUsage extends CModel
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
    protected $table = 'voucher_usage';
    
    /**
	 * The database table primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'voucher_usage_id';  
      

    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'voucher_usage_key';
    
    protected $keyGenerate = true;

    
    /**
     * Table fillable column names
     *
     * @var array
     */
    protected $fillable = ['voucher_usage_key','voucher_id','beneficiary_type','beneficiary_id','used_date','order_id'];



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
		return self::where(self::uniqueKey(),$key)->first();
      
    }
}
