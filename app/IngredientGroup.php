<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\{
    IngredientLang,
    Scopes\VendorScope
};
use Common;
use DB;
use App;


class IngredientGroup extends CModel
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
	protected $table = 'ingredient_group';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'ingredient_group_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'ingredient_group_key';

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
    protected $guarded = ['ingredient_group_name','price','csrf-token'];    
    

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
        return new IngredientGroupLang();
    }

   
    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();
        $query = self::select($self->getTable().'.*');
        IngredientGroupLang::selectTranslation($query);        
        return $query;
	}
    



	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{    
		return self::where(self::uniqueKey(),$key)->first();
    }

    /**
     * @var Ingredient Type
     */
    public function getIngredientTypes($typeId = null)
    {
        $ingredientTypes =  [            
                INGREDIENT_TYPE_MODIFIER =>  __('admincrud.Modifier'),
                INGREDIENT_TYPE_SUBCOURSE => __('admincrud.Sub Course'),
            ];
        return ($typeId != null) ? $ingredientTypes[$typeId] : $ingredientTypes;        
    }

    public static function getIngredient()
    {
        $self = new self();
        $ingredientName = self::getList()->where([$self->getTable().'.status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($ingredientName,'ingredient_group_name','ingredient_group_id');    
    }

    public static function getEditIngredient($itemId)
    {
        $self = new self();
        $ingredientGroup = new IngredientGroup();
        $query = self::select($ingredientGroup->getTable().'.ingredient_group_id')
        ->leftJoin($ingredientGroup->getTable(),$self->getTable().'.ingredient_group_id','=',$ingredientGroup->getTable().'.ingredient_group_id');
        IngredientGroupLang::selectTranslation($query);
        $query = $query->where([$self->getTable().'.item_id' => $itemId])->get()->toArray();            
        return array_column($query,'ingredient_group_name','ingredient_group_id');
    }

}
