<?php

namespace App\Api;

use App\Ingredient as CommonIngredient;
use App\Http\Resources\Api\V1\IngredientResource;
use App\Api\IngredientGroupMapping;
use App\Api\IngredientLang;

class Ingredient extends CommonIngredient
{
    public static function getIngredients($ingredientGroupId = null, $ingredientId = [])
    {
        $ingredient = new self();
        $ingredientGroupMapping = new IngredientGroupMapping();
        $query = $ingredientGroupMapping::select([
                $ingredientGroupMapping->getTable().'.price',
                $ingredient->getTable().'.*'
            ])
            ->leftjoin($ingredient->getTable(),$ingredientGroupMapping->getTable().'.ingredient_id',$ingredient->getTable().'.ingredient_id')
            ->where([
                    $ingredient->getTable().'.status' => ITEM_ACTIVE,
                ]);
            if($ingredientGroupId !== null) {
                $query = $query->where([
                    $ingredientGroupMapping->getTable().'.ingredient_group_id' => $ingredientGroupId
                ]);
            }                
            if($ingredientId !== null && is_array($ingredientId)) {
                $query = $query->whereIn($ingredient->getTable().'.ingredient_id',explode(',',$ingredientId));
            }            
        IngredientLang::selectTranslation($query);        
        $query = $query->where(Ingredient::tableName().".status",ITEM_ACTIVE)->whereNull($ingredient->getTable().'.deleted_at');
        return $query;
    }
}
