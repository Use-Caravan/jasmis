<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\BranchUser as Authenticatable;
use Laravel\Passport\HasApiTokens;
use SMartins\PassportMultiauth\PassportMultiauth;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Common;

class BranchUser extends CAuthModel
{
    use Notifiable, HasMultiAuthApiTokens; /* HasApiTokens */

    use SoftDeletes;
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'branch_user';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'branch_user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branch_id', 'username', 'email', 'phone_number', 'password',
        'device_type', 'device_token'
    ];

    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'branch_user_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;
    
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
