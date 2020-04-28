<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Api\User;
use FileHelper;

class ContactResource extends JsonResource
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
            'app_address' => ($this->configuration_name === 'app_address') ? $this->configuration_value : '',
            'app_email' => ($this->app_email === null) ? '' : $this->app_email,            
            'app_contact_number' => ($this->app_contact_number === null) ? '' : $this->app_contact_number,                        
            'app_latitude' => ($this->app_latitude === null) ? '' : $this->app_latitude,            
            'app_longitude' => ($this->app_longitude === null) ? '' : $this->app_longitude,            
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
