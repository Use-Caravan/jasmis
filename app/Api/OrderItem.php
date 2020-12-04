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
                    (SELECT GROUP_CONCAT( `ingredient_price` ) FROM `order_ingredient` AS OIL
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_price'),
		        OrderItem::tableName().".item_quantity",
                OrderItem::tableName().".item_subtotal"
            ])->where([
            OrderItem::tableName().'.order_id' => $orderID
            ])
        ->leftJoin(OrderIngredient::tableName(),OrderItem::tableName().'.order_item_id','=',OrderIngredient::tableName().'.order_item_id');
	OrderItemLang::selectTranslation($query);
	//print_r($query->groupBy(OrderItem::tableName().'.order_item_id')->get());exit;
	//echo '<pre>'; var_dump($query->groupBy(OrderItem::tableName().'.order_item_id')->toSql()); exit;
        return $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
	//return $query->groupBy('order_ingredient.order_ingredient_id')->get();
    }

    /*public static function getOrderItemsByItemId($orderItemID)
    {        
        //echo $orderItemID;exit;
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
                    (SELECT GROUP_CONCAT( `ingredient_price` ) FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_price'),
              /* DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_id` ) FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_ids'),*/
                /*DB::raw('group_concat( DISTINCT(order_ingredient_id) ) as ingredient_ids'),
            
                OrderItem::tableName().".item_quantity",
                OrderItem::tableName().".item_subtotal"
            ])->where([
            OrderItem::tableName().'.order_item_id' => $orderItemID
            ])
        ->leftJoin(OrderIngredient::tableName(),OrderItem::tableName().'.order_item_id','=',OrderIngredient::tableName().'.order_item_id');
	//print_r($query->get());exit;
        OrderItemLang::selectTranslation($query);
	//print_r($query->groupBy(OrderItem::tableName().'.order_item_id')->get());exit;
        return $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
	//return $query->groupBy('order_ingredient.order_ingredient_id')->get();
    }*/
    
    public static function getOrderItemsByItemId($orderItemID)
    {        
        $query = OrderItem::select([
                OrderItem::tableName().".*",
		        DB::raw('group_concat( DISTINCT(order_ingredient_id) ) as ingredient_ids'),
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
                    (SELECT GROUP_CONCAT( `ingredient_price` ) FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_price'),
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_quanitity` ) FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_quantity'),
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_subtotal` ) FROM `order_ingredient` AS OIL 
                    WHERE OIL.order_item_id = order_item.order_item_id)
                 as ingredient_subtotal'),
                OrderItem::tableName().".item_quantity",
                OrderItem::tableName().".item_subtotal"
            ])->where([
            OrderItem::tableName().'.order_item_id' => $orderItemID
            ])
        ->leftJoin(OrderIngredient::tableName(),OrderItem::tableName().'.order_item_id','=',OrderIngredient::tableName().'.order_item_id');
	//print_r($query->get());exit;

	$orderItems = $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
	//print_r($orderItems);exit;

	$ingredient_ids = $orderItems[0]["ingredient_ids"]; //echo $ingredient_ids;exit;
		    $ingredient = new OrderIngredient();	
                    $query = $ingredient::select([
                		$ingredient->getTable().'.*'
            			]);//print_r(explode(',',$ingredient_ids));exit;          
		    $query = $query->whereIn($ingredient->getTable().'.order_ingredient_id',explode(',',$ingredient_ids)); 
		    //print_r($query->get());exit;
		    OrderIngredientLang::selectTranslation($query);
		    //print_r($query->get());exit;
		    return $query->get();

        /*OrderItemLang::selectTranslation($query);
	//print_r($query->groupBy(OrderItem::tableName().'.order_item_id')->get());exit;
        return $query->groupBy(OrderItem::tableName().'.order_item_id')->get();
	//return $query->groupBy('order_ingredient.order_ingredient_id')->get();*/
    }

    
     public static function getOrderItemsByIngredientsId($orderIngredientsID)
    {        
       //  $query = DB::select("SELECT * FROM order_ingredient_lang WHERE order_ingredient_id = '$orderIngredientsID'");
        
        //return $query;
        //print_r($query->groupBy(OrderIngredientLang::tableName().'.order_ingredient_id')->get());exit;
      //  return $query;
         $query = OrderIngredientLang::select([
                  OrderIngredientLang::tableName().".*",
                DB::raw(' 
                    (SELECT GROUP_CONCAT( `ingredient_name` ) FROM `order_ingredient_lang` AS OIL 
                    LEFT JOIN order_ingredient AS OI ON OI.order_ingredient_id = OIL.order_ingredient_id
                    WHERE  OI.order_item_id = order_item.order_item_id AND language_code = "ar")
                 as ingredients_arabic'),
            ])->where([
            OrderIngredientLang::tableName().'.order_ingredient_id' => $orderIngredientsID
            ])
                 
        ->leftJoin(OrderIngredient::tableName(),OrderIngredientLang::tableName().'.order_ingredient_id','=',OrderIngredient::tableName().'.order_ingredient_id');
         
        OrderIngredientLang::selectTranslationArabic($query);
        return $query->groupBy(OrderIngredientLang::tableName().'.order_ingredient_id')->get();
        print_r($query);exit;
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
