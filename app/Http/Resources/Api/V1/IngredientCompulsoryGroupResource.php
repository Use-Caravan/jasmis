<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\Ingredient;
use App\Api\IngredientGroupLang;

class IngredientCompulsoryGroupResource extends JsonResource 
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($requestIngredientGroupLang)
    {
        // return parent::toArray($request);
        return [
            'ingredient_group_id' => $this->ingredient_group_id,            
            'ingredient_group_key' => $this->ingredient_group_key,
            'ingredient_group_name' => $this->ingredient_group_name,
            'arabic_ingredient_group_name' => IngredientGroupLang::where('ingredient_group_id',$this->ingredient_group_id)->where('language_code','ar')->value('ingredient_group_name'),
            'ingredient_type' => $this->ingredient_type,
            'minimum' => ($this->minimum === null) ? 0 : $this->minimum,
            'maximum' => ($this->maximum === null) ? 0 : $this->maximum,
            'sort_no' => ($this->sort_no === null) ? 0 : $this->sort_no,
            'ingredient_ids' => $this->ingredient_ids,
            'ingredients' => $this->when(
                $this->ingredient_ids, function(){
                    return IngredientResource::collection(Ingredient::getIngredients($this->ingredient_group_id, $this->ingredient_ids)->get() );
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
