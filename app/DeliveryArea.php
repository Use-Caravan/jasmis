<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\LanguageScope;
use App\Area;
use App\City;
use App\Country;
use Common;
use DB;
use App;

class DeliveryArea extends CModel
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
	protected $table = 'delivery_area';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'delivery_area_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'delivery_area_key';
    
     /**
     * The attributes that enable to generate unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;
    
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['delivery_area_name','csrf-token'];    

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
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new DeliveryAreaLang();
    }


    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{        
        $self = new self();
        $query = self::select($self->getTable().'.*');                
        DeliveryAreaLang::selectTranslation($query);                	        
        return $query;
	} 

	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{        
		return self::where(self::uniqueKey(), $key)->first();
    }

    public static function getAll()
    {
        $country = new Country();
        $city = new City();        
        $area = new Area();
        $self = new self();
        $query = self::getList()->leftJoin(
            $city->getTable(),
            $self->getTable().'.city_id',
            '=',
            $city->getTable().'.city_id'
        );
        CityLang::selectTranslation($query,'CL');        
		$query = $query->leftJoin(
            $area->getTable(),
            $self->getTable().'.area_id',
            '=',
            $area->getTable().'.area_id'
        );
        AreaLang::selectTranslation($query);
        return $query;
    }

    public static function getZonetype($zoneID = null)
    {
        $zoneTypes = [
                DELIVERY_AREA_ZONE_CIRCLE =>  __('admincrud.Circle'),
                DELIVERY_AREA_ZONE_POLYGON =>  __('admincrud.Polygon'),
        ];
        return ($zoneID !== null && isset($zoneTypes[$zoneID])) ? $zoneTypes[$zoneID] : $zoneTypes;
    }

    public static function getDeliveryAreaByArea($areaId)
    {   
        $self = new self();
        $deliveryAreaList =  self::getList()->where(
        [
            $self->getTable().'.status' => ITEM_ACTIVE,
            $self->getTable().'.area_id' => $areaId
        ])->get()->toArray();        
        return array_column($deliveryAreaList,'delivery_area_name','delivery_area_id'); 
    }

    
}
