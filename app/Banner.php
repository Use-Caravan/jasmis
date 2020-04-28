<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\BannerLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class Banner extends CModel
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
	protected $table = 'banner';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'banner_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'banner_key';


     /**
     * Table key generate variable 
     * 
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['banner_name','banner_file','csrf-token'];    

    
    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new BannerLang();
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
        BannerLang::selectTranslation($query);        
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

    public static function getBannerImage()
	{
        $banner = self::getList()->where(['status' => ITEM_ACTIVE]);
        if(request()->is_home_banner !== null) {
            $banner = $banner->where('is_home_banner',1)->first();
        } else {
            $banner = $banner->get();
        }
        return ($banner == null) ? null : $banner->toArray();         
	}
   
}
