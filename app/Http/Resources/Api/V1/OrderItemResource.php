<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;
use FileHelper;
use App\Api\ItemLang;
use App\Api\IngredientLang;
use App\Api\OrderIngredient;
use App\Api\OrderIngredientLang;

class OrderItemResource extends JsonResource
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
        $order_ingredient = OrderIngredientResource::collection($order_ingredient);
        //print_r($order_ingredient);exit;

        return [
            'item_name' => $this->item_name,
            'arabic_item_name' => ItemLang::where('item_id',$this->item_id)->where('language_code','ar')->value('item_name'),
            'item_subtotal' => Common::currency($this->item_subtotal),
            'item_quantity' => $this->item_quantity,
            'item_image_path' => FileHelper::loadImage($this->item_image_path),
            'item_description' => $this->item_description,
            'arabic_item_description' => ItemLang::where('item_id',$this->item_id)->where('language_code','ar')->value('item_description'),
            'ingredients' => $this->ingredients,
            'arabic_ingredients' => $this->arabic_ingredients,
            /*'ingredient_price' => $this->ingredient_price,
            'ingredient_quanitity' => $this->ingredient_quanitity,
            'ingredient_subtotal' => $this->ingredient_subtotal,*/
            'order_ingredients' => $order_ingredient,
            'price_on_selection' => $this->price_on_selection,
            'sub_items' => ( isset( $this->price_on_selection ) && $this->price_on_selection == 1 && !empty( isset( $this->price_on_selection_options ) ) ) ? json_decode( $this->price_on_selection_options ) : [],
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
