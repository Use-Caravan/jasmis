<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\CategoryLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class Category extends CModel
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
	protected $table = 'category';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'category_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'category_key';

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
    protected $guarded = ['category_name','csrf-token'];        

    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new CategoryLang();
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
        CategoryLang::selectTranslation($query);
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

    /**
    * @var return category as associative array and index array
    * @param boolean $pair
    */
    public static function getCategory()
    {
    	$categoryName = self::getList()->where(['is_main_category' => MAIN_CATEGORY])->get()->toArray();
        return array_column($categoryName,'category_name','category_id');	
    } 

    public static function getBranchCategory()
    {
        $branchCategory = self::getList()->where(['status' => ITEM_ACTIVE])->get()->toArray(); 
        return array_column($branchCategory,'category_name','category_id','category_image'); 
    }   
}
