<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CAuthModel;
use App\DeliveryboyLang;
use Common;
use Auth;

class Deliveryboy extends CAuthModel 
{
    use Notifiable, SoftDeletes;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'deliveryboy';
    
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'deliveryboy_id';

    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'deliveryboy_key';

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
    protected $guarded = ['deliveryboy_name','address','confirm_password','csrf-token']; 

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    

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
        $deliveryBoy = new Deliveryboy();
        $query = self::select($self->getTable().".*");
        DeliveryboyLang::selectTranslation($query);
        return $query;
    }

    public static function showDeliveryboy($deliveryboyId)
    {
        $query = self::getList()
        ->where(['deliveryboy_key' => $deliveryboyId]);
        $data = $query->first();
        return $data;
    }

    public function approvedStatus($approvedStatus = null)
    {          
        $options = [
            DELIVERY_BOY_APPROVED_STATUS_PENDING      => __('admincrud.Pending'),
            DELIVERY_BOY_APPROVED_STATUS_APPROVED  => __('admincrud.Approved'),
            DELIVERY_BOY_APPROVED_STATUS_REJECTED     => __('admincrud.Rejected')
        ];                
        return ($approvedStatus !== null && isset($options[$approvedStatus])) ? $options[$approvedStatus] : $options;
    }

    public function onlineStatus($status = null)
    {
    /*  driver_active: 1,
        driver_online: 2,
        driver_offline: 3,
        driver_inactive: 4,
        driver_deactive: 5,
        driver_busy: 6,
        driver_deleted: 7,
        driver_stop_duty: 8, */

        $onlineStatus = [
            DRIVER_ACTIVE => 'Active',
            DRIVER_ONLINE => 'Online',
            DRIVER_OFFLINE => 'Offline',
            DRIVER_INACTIVE => 'Inactive',
            DRIVER_DEACTIVE => "Deactive",
            DRIVER_BUSY => 'Busy',
            DRIVER_DELETED => 'Deleted',
            DRIVER_STOP_DUTY => 'Stop Duty',
        ];
        return ($status === null) ? $onlineStatus : $onlineStatus[$status];
    }

    
}
