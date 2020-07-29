<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;
use App\OrderItem as CommonOrderItem;

use App\Api\OrderIngredient;
use App\Api\OrderIngredientLang;
use DB;
use App;

class OrderItem extends CommonOrderItem
{
    public static function getOrderItems($orderID)
    {        
        $query = OrderItem::select([
                OrderItem::tableName().".*",
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_name` ) FROM `order_ingredient_lang` AS OIL 
                    LEFT JOIN order_ingredient AS OI ON OI.order_ingredient_id = OIL.order_ingredient_id
                    WHERE  OI.order_item_id = order_item.order_item_id AND language_code = "'.App::getLocale().'")
                 as ingredients'),
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_name` ) FROM `order_ingredient_lang` AS OIL 
                    LEFT JOIN order_ingredient AS OI ON OI.order_ingredient_id = OIL.order_ingredient_id
                    WHERE  OI.order_item_id = order_item.order_item_id AND language_code = "ar")
                 as arabic_ingredients'),
               DB::raw(' 
                    (SELECT `ingredient_price` FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_price'),
                OrderItem::tableName().".item_quantity",
                OrderItem::tableName().".item_subtotal"
            ])->where([
            OrderItem::tableName().'.order_id' => $orderID
            ])
        ->leftJoin(OrderIngredient::tableName(),OrderItem::tableName().'.order_item_id','=',OrderIngredient::tableName().'.order_item_id');
        OrderItemLang::selectTranslation($query);
        return $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
    }
    
    public static function getOrderItemsArabic($orderID)
    { 
    $query = OrderItem::select([
                OrderItem::tableName().".*",
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_name` ) FROM `order_ingredient_lang` AS OIL 
                    LEFT JOIN order_ingredient AS OI ON OI.order_ingredient_id = OIL.order_ingredient_id
                    WHERE  OI.order_item_id = order_item.order_item_id AND language_code = "ar")
                 as ingredients_arabic'),
                OrderItem::tableName().".item_quantity",
                OrderItem::tableName().".item_subtotal"
            ])->where([
            OrderItem::tableName().'.order_id' => $orderID
            ])
        ->leftJoin(OrderIngredient::tableName(),OrderItem::tableName().'.order_item_id','=',OrderIngredient::tableName().'.order_item_id');
        OrderItemLang::selectTranslationArabic($query);
        return $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
    }
}
