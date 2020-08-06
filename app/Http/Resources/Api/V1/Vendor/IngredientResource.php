<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;
use App\Api\OrderItem;
use App\Api\OrderIngredientLang;

class IngredientResource extends JsonResource
{
    public function toArray($request)
    {
        /*return [ 
                'ingredient_ids' => $this->ingredient_ids,               
                'ingredients' => $this->when(
                $this->ingredient_ids, function(){                    
                    //print_r($orderItems);exit;
                    return IngredientViewResource::collection($orderItems = OrderItem::getOrderItemsByIngredientsId($this->ingredient_ids));
                }
            ),
        ];*/
        
        $arabic_ingredient_name = OrderIngredientLang::where('order_ingredient_id',$this->order_ingredient_id)->where('language_code','ar')->value('ingredient_name');
        return [ 
            'ingredient_name' => ($this->ingredients === null) ? (($this->ingredient_name === null) ? '' : $this->ingredient_name ) : $this->ingredients,
            'arabic_ingredients' => ($this->arabic_ingredients === null) ? (($arabic_ingredient_name === null) ? '' : $arabic_ingredient_name ) : $this->arabic_ingredients,
            'price' => Common::currency($this->ingredient_price),
	    //'price' => $ingredient_price_str
        ];
	/*$ingredient_price_arr = explode( ",", $this->ingredient_price );
	$ingredient_price_str = array();
	foreach( $ingredient_price_arr as $ingredient_price )
	{
		$ingredient_price_str[] = Common::currency($ingredient_price);
	}
	$ingredient_price_str = implode(",", $ingredient_price_str);
        // return parent::toArray($request);
        return [ 
            'ingredient_name' => ($this->ingredients === null) ? '' : $this->ingredients,
            'arabic_ingredients' => ($this->arabic_ingredients === null) ? '' : $this->arabic_ingredients,
            //'price' => Common::currency($this->ingredient_price),
	    'price' => $ingredient_price_str
        ];*/
    }
    
    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function  with($request)
    {
        return [
            'status' => Response::HTTP_OK,
            'time' => strtotime(date('Y-m-d H:i:s')),
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'kjh');
    }
}
