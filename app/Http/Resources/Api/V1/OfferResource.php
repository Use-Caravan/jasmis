<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\BranchTimeslot;
use App\Helpers\FileHelper;
use Common;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        return [
            'color_code' => '#'.$this->color_code,
            'branch_key' => $this->branch_key,
            'branch_slug' => $this->branch_slug,
            'offer_key' => $this->offer_key,
            'offer_enable' => true,
            'offer_type' => $this->offer_type,
            'offer_value' => $this->when(true,function() {
                if($this->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return Common::currency($this->offer_value);
                } else {
                    return $this->offer_value." %";
                }
            }),
            'offer_name' => $this->offer_name,
            'offer_banner' => FileHelper::loadImage($this->offer_banner),
            'branch_name' => $this->branch_name,
            'branch_logo' => FileHelper::loadImage($this->branch_logo),
            'branch_address' => $this->branch_address,
            'category_key' => $this->category_key,
            'item_key' => $this->item_key,
            
            'offer_price' => $this->when(true,function() {
                if($this->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return Common::currency($this->item_price - $this->offer_value);
                } else {
                    return Common::currency( $this->item_price - (($this->item_price/100) * $this->offer_value) );
                }
            }),
            'item_price' => Common::currency($this->item_price),
            'item_name' => $this->item_name,
            'item_image' => FileHelper::loadImage($this->item_image),
            'item_description' => $this->item_description,
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
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'display_in_home' => $this->display_in_home,
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
