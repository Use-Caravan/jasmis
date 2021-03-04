<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\{
    Api\Cuisine,
    Api\BranchCuisine,
    Api\BranchTimeslot,
    Api\CuisineLang
};
use DB;
use FileHelper;

class UserWishlistResource extends JsonResource
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
        $branch_cuisine = isset( $this->cuisines ) ? explode(',',$this->cuisines) : [];
        if( count($branch_cuisine) > 1 ) {
            for ($i = 1; $i < count($branch_cuisine); $i++) {
                $branch_cuisine[$i] = " ".$branch_cuisine[$i];
            }
        }
        $branch_cuisine = implode(',',$branch_cuisine);
        //print_r($branch_cuisine);exit;

         return [            
            'vendor_id' => $this->vendor_id,
            'vendor_key' => $this->vendor_key,
            'vendor_name' => $this->vendor_name,
            'branch_id' => $this->branch_id,
            'branch_key' => $this->branch_key,
            'branch_slug' => $this->branch_slug,
            'branch_name' => $this->branch_name,
            'branch_avg_rating' => ($this->branch_avg_rating === null) ? 0 : number_format($this->branch_avg_rating,1),            
            //'cuisines' => $this->cuisines,
            'cuisines' => $branch_cuisine,
            'branch_logo' => FileHelper::loadImage($this->vendor_logo),
            'color_code' => ($this->color_code === null) ? '' : '#'.$this->color_code,
            'availability_status' => $this->when(true,function(){
                $availabilityStatus = $this->availability_status;
                $currentTime = date('H:i:s');
                if($availabilityStatus === AVAILABILITY_STATUS_OPEN) {
                    $slot = BranchTimeslot::where([
                        'branch_id' => $this->branch_id,
                        'status' => ITEM_ACTIVE,
                        'day_no' => date("N", strtotime(date('Y-m-d H:i:s'))),
                        ])
                        ->whereRaw('start_time < "'.$currentTime.'" and end_time > "'.$currentTime.'"')
                        ->first();                    
                    if($slot === null){
                        return AVAILABILITY_STATUS_CLOSED;
                    }
                }
                return $availabilityStatus;
            }),
            'zone_type' => ($this->zone_type === null) ? "" : $this->zone_type
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
