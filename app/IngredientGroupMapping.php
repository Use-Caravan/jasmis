<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{
    Ingredient,
    IngredientGroup,
    Scopes\LanguageScope
};
use Common;
use DB;
use App;


class IngredientGroupMapping extends CModel
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ingredient_group_mapping';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'ingredient_group_mapping_id';
        

    /**
     * Off timestampt to insert
     *
     * @var bool
     */
    public $timestamps = false;

	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['ingredient_group_id','ingredient_id','price','default_status'];    
    

    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }            


    /**
     * @return array exists ingredients
     * @param int $ingredientGroupId
     * @param array $ingredientid
     */
    public static function getExistsIngredients($ingredientGroupID = null, $ingredientID = [])
    {
        $self = new self();
        $ingredient = new Ingredient();
        if($ingredientGroupID != null && empty($ingredientID)) {
            $query = self::from( $self->getTable().' as IGM' )->select('IGM.*')
            ->leftJoin($ingredient->getTable(), "IGM.ingredient_id",'=',$ingredient->getTable().'.ingredient_id')
            ->whereNull($ingredient->getTable().".deleted_at");
            IngredientLang::selectTranslation($query);
            return $query->where('IGM.ingredient_group_id', $ingredientGroupID)->groupBy('IGM.ingredient_id')->get()->toArray();
        }
        else if($ingredientGroupID == null && !empty($ingredientID)) {
            foreach($ingredientID as $key => $value) {
                $In[] = $key;
            }
            return Ingredient::getList()->whereIn($ingredient->getTable().'.ingredient_id', $In)->groupBy($ingredient->getTable().'.ingredient_id')->get()->toArray();
        }
    }
}
