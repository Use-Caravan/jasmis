<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Common;

class IngredientResource extends JsonResource
{
    public function toArray($request)
    {
	$ingredient_price_arr = explode( ",", $this->ingredient_price );
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
