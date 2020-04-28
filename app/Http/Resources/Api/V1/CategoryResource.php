<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Api\Category;
use App\Api\Item;
use FileHelper;

class CategoryResource extends JsonResource
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
            //'branch_id' => $this->branch_id,
            'category_id' => $this->category_id,            
            'category_key' => $this->category_key,
            'category_name' => $this->category_name,
            'category_image' => FileHelper::loadImage($this->category_image),
            'is_main_category' => $this->is_main_category,
            'category_count'    => $this->category_count,
            'branch_key'    => $request->branch_key,
            'sort_no' => ($this->sort_no === null) ? 0 : $this->sort_no, 
            /* 'sub_category' => $this->when( ($this->is_main_category == 1) ,function()
            {
                $category = new Category();
                $subCategory = $category::getList()->where([
                    $category->getTable().".status" => ITEM_ACTIVE,
                    $category->getTable().".is_main_category" => 2,
                    $category->getTable().".main_category_id" => $this->category_id,
                ])->get();
                return CategoryResource::collection($subCategory);
            }), */
            $this->mergeWhen( ($request->branch_key && $this->is_main_category == 1), [
                'items' => ItemResource::collection(Item::getItems(null,$request->branch_key,$this->category_id)->get()),
            ]),
        ];
    }
    
    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function with($request)
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
