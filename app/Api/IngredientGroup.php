<?php

namespace App\Api;

use App\IngredientGroup as CommonIngredientGroup;
use App\Api\IngredientGroupMapping;
use App\Api\ItemGroupMapping;
use App\Api\IngredientGroupLang;
use DB;

class IngredientGroup extends CommonIngredientGroup
{
    public static function getIngredientGroups($itemId = null, $ingredientGroupId = null)
    {
        $itemGroupMapping = new ItemGroupMapping();           
        $ingredientGroup = new self();
        $ingredientGroupMapping = new IngredientGroupMapping(); 
        $ingredient = new Ingredient();       
        $ingredientQuery = ItemGroupMapping::addSelect([
                $ingredientGroup->getTable().'.*',                        
                DB::raw('group_concat( DISTINCT(ingredient_id) ) as ingredient_ids'),   
            ])
        ->leftjoin($ingredientGroup->getTable(),$itemGroupMapping->getTable().'.ingredient_group_id','=',$ingredientGroup->getTable().'.ingredient_group_id')
        ->leftjoin($ingredientGroupMapping->getTable(),$ingredientGroup->getTable().'.ingredient_group_id','=',$ingredientGroupMapping->getTable().'.ingredient_group_id');
        IngredientGroupLang::selectTranslation($ingredientQuery,'IGL');
        if($itemId !== null) {            
            $ingredientQuery = $ingredientQuery->where([
                $itemGroupMapping->getTable().'.item_id' => $itemId
            ]);                        
        }
        if($ingredientGroupId !== null) {
            if(is_integer($ingredientGroupId)) {
                $ingredientQuery = $ingredientQuery->where([
                    $ingredientGroup->getTable().'.ingredient_group_id' => $ingredientGroupId
                ]);
            }
            if(is_string($ingredientGroupId)) {
                $ingredientQuery = $ingredientQuery->where([
                    $ingredientGroup->getTable().'.ingredient_group_key' => $ingredientGroupId
                ]);
            }
        }        
        return $ingredientQuery->where(IngredientGroup::tableName().".status",ITEM_ACTIVE)->whereNull(IngredientGroup::tableName().".deleted_at")->groupBy($itemGroupMapping->getTable().'.ingredient_group_id');
    }
}
