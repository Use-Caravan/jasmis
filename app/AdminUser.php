<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{
    Scopes\LanguageScope,
    AdminUser,
    CAuthModel,
    Role
};
use Common;
use Session;
use Auth;

class AdminUser extends CAuthModel
{
    use Notifiable, SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'admin_user';
    
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'admin_user_id';

    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'admin_user_key';

    /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;


    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['first_name','last_name','address','confirm_password','csrf-token']; 

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
        $this->notify(new AdminResetPasswordNotification($token));
    }

    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }  

    public static function getAuthUserID()
    {
        return Auth::guard(APP_GUARD)->user()->admin_user_id;
    }
    
    /**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }

    public static function getList()
    {    
        $self = new self();
        $self = $self->getTable();
        $deliveryBoy = new AdminUser();
        $query = self::select("$self.*");
        /* AdminUserLang::selectTranslation($query); */
        return $query;
    }

    /**
     * Auth logout
     */
    public static function authLogout()
    {                
        $user = Auth::guard(APP_GUARD)->user();
        switch (APP_GUARD) {
            case GUARD_ADMIN:                                            
                $causer_id = $user->admin_id;
                $causer_name = $user->username;
                break;
            case GUARD_VENDOR:
                $causer_id = $user->vendor_id;
                $causer_name = $user->username;
                break;
            case GUARD_OUTLET:
                $causer_id = $user->branch_id;
                $causer_name = $user->email;
                break;
        }
        Common::log("User Logout","$causer_name User has logout",new AdminUser, $causer_id, $causer_name);
        Auth::guard(APP_GUARD)->logout();
        Session::flush();        
        return true;
    }


    public static function getAll()
    {
        $role = new Role();
        $role = $role->getTable();
        $self = new self();
        $self = $self->getTable();
        return self::getList()->addSelect("$role.role_name")->leftJoin($role,"$self.role_id",'=',"$role.role_id")->where('admin_user.user_type',SUB_ADMIN);
    }

    /** Admin  user types */
    public function userTypes()
    {
        $userType = [ADMIN => 'Admin',SUB_ADMIN => 'Sub Admin'];
        return $userType;

    }   
}
