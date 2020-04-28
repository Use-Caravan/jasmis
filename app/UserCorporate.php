<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Common;

class UserCorporate extends CModel
{
    
    use SoftDeletes;
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_corporate';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_corporate_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corporate_name',
        'contact_name',
        'office_email',
        'mobile_number',
        'contact_address',
        'voucher_description',
        'is_booked',
    ];

    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'user_corporate_key';
    
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
}
