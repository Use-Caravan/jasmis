<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;
use FileHelper;
use App\Api\OrderItem;
use App\Api\IngredientGroup;
use App\Http\Resources\Api\V1\IngredientViewOrderResource;

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
        return [
            'item_name' => $this->item_name, 
            'item_price' => $this->base_price,                       
            'item_subtotal' => Common::currency($this->item_subtotal),
            'item_quantity' => $this->item_quantity,
            'item_image_path' => FileHelper::loadImage($this->item_image_path),
            'item_description' => $this->item_description,
            'ingredients' => $this->when(
                $this->order_item_id, function(){
                    //$orderItems = OrderItem::getOrderItems($this->order_id); 
		    $orderItems = OrderItem::getOrderItemsByItemId($this->order_item_id);
		    //print_r($orderItems);exit;                   
                    return IngredientResource::collection($orderItems);
                }
            ),
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
