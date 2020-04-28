<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IngredientLang;
use App\Scopes\VendorScope;
use Common;
use DB;
use App;


class Ingredient extends CModel
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
	protected $table = 'ingredient';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'ingredient_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'ingredient_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['ingredient_name','csrf-token'];    
    

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope());
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
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new IngredientLang();
    }


    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        IngredientLang::selectTranslation($query);
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
     * @return array $query active ingredient list
     * @param array $exists ignore exists ingredient  on edit function
     */
    public static function getActiveIngredients($exists = null, $vendorId = null)
    {
        $self = new self();
        $query = self::getList()->where(['status' => ITEM_ACTIVE]);
        if($exists !== null){
            $notIn = array_column($exists,'ingredient_id');
            $query = $query->whereNotIn($self->getTable().'.ingredient_id', $notIn);
        }
        if($vendorId !== null) {
            $query = $query->where('vendor_id',$vendorId);
        }        
        return $query->get()->toArray();        
    }
}
