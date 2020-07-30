<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;

class IngredientResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [ 
            'ingredient_name' => ($this->ingredients === null) ? '' : $this->ingredients,
            'arabic_ingredients' => ($this->arabic_ingredients === null) ? '' : $this->arabic_ingredients,
            'price' => Common::currency($this->ingredient_price),
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
