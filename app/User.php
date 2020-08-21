<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\UserResetPasswordNotification;
use Laravel\Passport\HasApiTokens;
use SMartins\PassportMultiauth\PassportMultiauth;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use Common;

class User extends CAuthModel
{
    use Notifiable, HasMultiAuthApiTokens;

    use SoftDeletes;
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'email', 'phone_number', 'password', 'accept_terms_conditions',
        'profile_image', 'login_type', 'social_token', 'email_verified', 'email_verified_at', 
        'otp_verified', 'otp_verified_at', 'remember_token', 'device_type', 'device_token', 'gender',
        'dob','card_number', 'default_language', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function sendPasswordResetNotification($token)
    {        
        $this->notify(new UserResetPasswordNotification($token));
    }

    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'user_key';
    
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

    public static function getUsers()
    {
        $userList = self::where(['status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($userList,'email','user_id');
    }

    public static function getUsersVoucher()
    {
        //$userList = self::where(['status' => ITEM_ACTIVE, 'email' => NOT NULL])->get()->toArray();
        $userList = self::where(['status' => ITEM_ACTIVE])->where('email', '<>', '')->get()->toArray();
        return array_column($userList,'email','user_id');
    }

    public static function getAllUsers()
    {
        $allUsers = self::select(self::tableName().".*");
        return $allUsers;
    }

    public function deviceTypes($type = null)
    {
        $types = [
            DEVICE_TYPE_WEB => __('Web'),
            DEVICE_TYPE_ANDROID => __('Android'),
            DEVICE_TYPE_IOS => __('IOS'),
            DEVICE_TYPE_WINDOWS => __('Windows'),
        ];
        return ($type === null) ? $types : $types[$type];
        // return ($type !== null && isset($types[$type])) ? $types[$type] : $types;
    }


    public function loginTypes($type = null)
    {
        $types = [
            LOGIN_TYPE_APP => __('App'),
            LOGIN_TYPE_GP => __('Google Plus'),
            LOGIN_TYPE_FB => __('Facebook'),
        ];
        return ($type === null) ? $types : $types[$type];
    }

    
}
