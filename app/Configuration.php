<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Language;
use App;
use Auth;
use Session;

class Configuration extends CModel
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
	protected $table = 'configuration';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'configuration_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'configuration_key';

    /**
     * The attributes that table key generate
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token'];    


    public static function tableName()
    {
        $self = new self();
        return $self->table;
    }
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
        $query = self::select(self::tableName().'.*');
        return $query;
	}

	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where('city_key', $key)->first();
    }   

    /**
     * Set web app configurations
     * @param string $appType
     * @param object $request
     */
    public static function setConfiguration()
    {        
        $configuration = Configuration::get()->toArray();
        foreach($configuration as $key => $value){
            config(['webconfig.'.$value['configuration_name'] => $value['configuration_value']]);
        }                

        config(['mail.host' => config('webconfig.smtp_host') ]);
        config(['mail.username' => config('webconfig.smtp_username') ]);
        config(['mail.from.name' => config('webconfig.app_name') ]);
        config(['mail.password' => config('webconfig.smtp_password') ]);
        config(['mail.encryption' => config('webconfig.encryption') ]);
        config(['mail.port' => config('webconfig.port') ]);


        Language::setupLanguage();              

    }
    public function encryptionTypes()
    {
        return ['tls' => 'TLS','ssl' => 'SSL' ];
    }
    public function currencyPositions()
    {
        return [
            CURRENCY_RIGHT => __('admincommon.Right'),
            CURRENCY_LEFT => __('admincommon.Left')
        ];
    }
}
