<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Http\Traits\Ctraits;

use App\CityLang;
use App\Country;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class City extends CModel
{
     /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;
    //use Ctraits;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'city';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'city_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'city_key';

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
    protected $guarded = ['city_name','csrf-token'];    

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
        return new CityLang();
    }

    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();        
        $query = self::select($self->getTable().'.*');                
        CityLang::selectTranslation($query);                
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
        $country = new Country();
        $query = self::getList()->leftJoin($country->getTable(), $self->getTable().'.country_id','=', $country->getTable().".country_id");
        CountryLang::selectTranslation($query,'CYL');
        return $query;
    }

    public static function getCity($countryId)
    {
        $self = new self();
        $country_list = self::getList()->where([$self->getTable().'.status' => ITEM_ACTIVE, $self->getTable().'.country_id' => $countryId])->get()->toArray();        
        return array_column($country_list,'city_name','city_id');
    }
}
