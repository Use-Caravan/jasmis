<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CountryLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;


class Country extends CModel
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
	protected $table = 'country';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'country_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'country_key';

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
    protected $guarded = ['country_name','csrf-token'];    
    

    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new CountryLang();
    }

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
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        CountryLang::selectTranslation($query);        
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

    
    public static function getCountry()
    {
        $country_list = self::getList()->where(['status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($country_list,'country_name','country_id');
    }
}
