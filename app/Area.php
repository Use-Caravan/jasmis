<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AreaLang;
use App\Http\Traits\Ctraits;
use App\City;
use App\Country;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class Area extends CModel
{
     /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;//,Ctraits;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'area';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'area_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'area_key';
    
    /**
     * Table keygenerate variable 
     * 
     * @var string
     */
	protected $keyGenerate = true;   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['area_name','csrf-token'];    

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
        return new AreaLang();
    }
   
    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();        
        $query = self::from($self->getTable())->select($self->getTable().'.*');                
        AreaLang::selectTranslation($query);        
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
        $self = new self();
        $city = new City();
        $country = new Country();
        $query = self::getList()
        ->leftJoin($country->getTable(),$self->getTable().'.country_id','=',$country->getTable().'.country_id')
        ->leftJoin($city->getTable(),$self->getTable().'.city_id','=',$city->getTable().'.city_id');
        CountryLang::selectTranslation($query,'CYL');
		CityLang::selectTranslation($query,'CTL');
        return $query;
    }

    public static function getArea($city_id)
    {
        $self = new self();
        $areaList =  self::getList()->where(
            [
                $self->getTable().'.status' => ITEM_ACTIVE,
                $self->getTable().'.city_id' => $city_id
            ])->get()->toArray();        
        return array_column($areaList,'area_name','area_id');
    }
}
