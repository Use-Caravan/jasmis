<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use FileHelper; 
use App\Api\BranchTimeslot;
use Auth;
use Common;

class TimeInfoResource extends JsonResource
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
            'branch_id' => $this->branch_id,
            'timeslot_type' => $this->timeslot_type,
            'day_no' => $this->day_no,
            'day_name' => BranchTimeslot::getDays($this->day_no),
            'start_time' => Common::renderDate($this->start_time, 'h:i A'),
            'end_time' => Common::renderDate($this->end_time,' h:i A'),
            'time_slot' => Common::renderDate($this->start_time, 'h:i A')." - ".Common::renderDate($this->end_time,' h:i A'),
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
