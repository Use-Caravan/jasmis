<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Api\User;
use FileHelper;

class EnquiryResource extends JsonResource
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
            'first_name' => ($this->first_name === null) ? '' : $this->first_name,
            'last_name' => ($this->last_name === null) ? '' : $this->last_name,            
            'email' => ($this->email === null) ? '' : $this->email,                        
            'phone_number' => ($this->phone_number === null) ? '' : $this->phone_number,
            'subject' => ($this->subject === null) ? '' : $this->subject,
            'comments' => ($this->comments === null) ? '' : $this->comments,            
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
