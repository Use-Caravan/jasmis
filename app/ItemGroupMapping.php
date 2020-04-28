<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;

class ItemGroupMapping extends CModel
{    

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'item_group_mapping';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'item_group_mapping_id';

    /**
     * The attributes that disable timestamp
     *
     * @var string
     */
    public $timestamps = false;
    
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['item_id','ingredient_group_id'];    

   
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    } 

    
    public static function getExistsIngredient($itemId)
    {
        $self = new self();
        $ingredientGroup = new IngredientGroup();
        $query = self::select('ingredient_group_id')
                ->where(['.item_id' => $itemId])
                ->get()->toArray();
            return array_column($query,'ingredient_group_id');
    }   
}
