<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class UserAddressResource extends JsonResource
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
            'user_address_id' => $this->user_address_id,
            'user_address_key' => $this->user_address_key,
            'address_type_id' => $this->address_type_id,
            'address_type_name' => ($this->address_type_name === null) ? '' : $this->address_type_name,
            /* 'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id, */
            'latitude' => ($this->latitude === null) ? '' : $this->latitude,
            'longitude' => ($this->longitude === null) ? '' : $this->longitude,
            'apartment' => ($this->apartment === null) ? '' : $this->apartment,
            'building' => ($this->building === null) ? '' : $this->building,
            'street' => ($this->street === null) ? '' : $this->street,
            'area' => ($this->area === null) ? '' : $this->area,
            'landmark' => ($this->landmark === null) ? '' : $this->landmark,
            'company' => ($this->company === null) ? '' : $this->company,
            'floor' => ($this->floor === null) ? '' : $this->floor,
            'block' => ($this->block === null) ? '' : $this->block,
            'full_address' => ($this->full_address === null) ? '' : $this->full_address,
            'entrance_number' => ($this->entrance_number === null) ? '' : $this->entrance_number,
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
