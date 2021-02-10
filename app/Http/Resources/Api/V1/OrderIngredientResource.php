<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;
use FileHelper;
use App\Api\ItemLang;
use App\Api\IngredientLang;
use App\Api\OrderIngredient;
use App\Api\OrderIngredientLang;

class OrderIngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);

        $order_ingredient = OrderIngredient::where(OrderIngredient::tableName().'.order_item_id',$this->order_item_id)->get();
        //print_r($order_ingredient);exit;

        return [
            'order_ingredient_id' => $this->order_ingredient_id,
            'ingredient_id' => $this->ingredient_id,
            'ingredient_name' => OrderIngredientLang::where('order_ingredient_id',$this->order_ingredient_id)->where('language_code','en')->value('ingredient_name'),
            'arabic_ingredient_name' => OrderIngredientLang::where('order_ingredient_id',$this->order_ingredient_id)->where('language_code','ar')->value('ingredient_name'),
            'ingredient_price' => $this->ingredient_price,
            'ingredient_quanitity' => $this->ingredient_quanitity,
            'ingredient_subtotal' => $this->ingredient_subtotal,
        ];
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
