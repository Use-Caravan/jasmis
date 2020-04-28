<?php

namespace App;

use App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\CuisineLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;
use Validator;

class Cuisine extends CModel
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
	protected $table = 'cuisine';	

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'cuisine_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'cuisine_key';

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
    protected $guarded = ['cuisine_name','csrf-token'];        
    
    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new CuisineLang();
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
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }

	/**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        CuisineLang::selectTranslation($query);
        return $query;
	}	
    
    public static function getCuisine()
    {
        $self = new self();
        $cusineList = self::getList()->where([$self->getTable().'.status'=>ITEM_ACTIVE])->get()->toArray();
        return array_column($cusineList,'cuisine_name','cuisine_id');
    }

    public static function getCuisineNames($cuisineId)
    {  
        $cuisineId = explode(',', $cuisineId);  
        $self = new self();
        $cusineNames = self::getList()->whereIn($self->getTable().'.cuisine_id',$cuisineId)->get();
        return $cusineNames;
    }
}
