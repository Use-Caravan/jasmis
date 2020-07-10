<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Api\CuisineLang;

class CuisineResource extends JsonResource
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
            'cuisine_id' => ($this->cuisine_id === null) ? '' : $this->cuisine_id,            
            'cuisine_key' => ($this->cuisine_key === null) ? '' : $this->cuisine_key,
            'cuisine_name' => CuisineLang::where('cuisine_id',$this->cuisine_id)->where('language_code','en')->value('cuisine_name'),
            'arabic_cuisine_name' => CuisineLang::where('cuisine_id',$this->cuisine_id)->where('language_code','ar')->value('cuisine_name'),
            //'arabic_cuisine_name' => ($this->arabic_cuisine_name === null) ? '' : $this->arabic_cuisine_name,
            'sort_no' => ($this->sort_no === null) ? '' : $this->sort_no,
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
