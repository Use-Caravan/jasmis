<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use FileHelper; 
use App\{
    Api\Branch,
    Api\BranchReview,
    Api\BranchCuisine,
    Api\CuisineLang,
    Api\BranchTimeslot,
    Api\Cuisine,
    Api\UserWishlist,
    Api\Vendor,
    Api\Order,
    Api\Category
};
use Auth;
use Common;

class BranchResource extends JsonResource
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
        
        $vendor = new Vendor();

        return  [
            'branch_id' => $this->branch_id,
            'branch_key' => $this->branch_key,
            'branch_name' => $this->branch_name,
            'vendor_id' => $this->vendor_id,
            'vendor_key' => $this->vendor_key,
            'vendor_name' => $this->vendor_name,
            'branch_logo' => FileHelper::loadImage($this->vendor_logo),     
            'preparation_time' => $this->preparation_time,            
            'delivery_time' => ($this->delivery_time === null) ? 0 : $this->delivery_time,
            'pickup_time' => ($this->pickup_time === null) ? 0 : $this->pickup_time,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'min_order_value' => Common::currency($this->min_order_value),
            'branch_slug' => ($this->branch_slug === null) ? "" : $this->branch_slug,   
            'zone_radius' => $this->zone_radius,
            'payment_option' => $this->when(true, function() {
                if($this->payment_option == null) {
                    return '';
                }
                $payments = explode(',', $this->payment_option);
                $order = new Order();
                $name = '';
                foreach($payments as $key => $value) {
                    if($key+1 == count($payments)) {
                        $name .= $order->paymentTypes($value);
                    } else {
                        $name .= $order->paymentTypes($value).", ";
                    }                    
                }
                return $name;
            }),
            'distance' => ($this->distance === null) ? 0 : Common::round($this->distance),
            'branch_cuisine' => $this->branch_cuisine,
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
            'color_code' => ($this->color_code === null) ? '' : '#'.$this->color_code,
            'branch_avg_rating' => ($this->branch_avg_rating === null) ? 0 : Common::round($this->branch_avg_rating,1),
            'branch_rating_count' => ($this->branch_rating_count === null) ? 0 : $this->branch_rating_count,
            'branch_count' => ($this->branch_count === null) ? 0 : $this->branch_count,
            'delivery_cost' => ($this->delivery_cost === null) ? Common::currency(0) : Common::currency($this->delivery_cost),
            'is_wishlist' => $this->when(true,function() {                                 
                if ( (!auth()->guard(GUARD_USER_API)->check()) && (!auth()->guard(GUARD_USER)->check()) ) {
                    return 0;
                } else {
                    if(auth()->guard(GUARD_USER_API)->check()) {                        
                        $userID = request()->user(GUARD_USER_API)->user_id;
                    }                    
                    if(auth()->guard(GUARD_USER)->check()) {
                        $userID = request()->user(GUARD_USER)->user_id;
                    }
                    $count = UserWishlist::where([
                        'user_id' =>  $userID,
                        'branch_id' => $this->branch_id,
                        'status' => ITEM_ACTIVE,
                    ])->count();
                    return ($count > 0) ? 1 : 0;
                }
            }),
            'branch_offer' => 'Buy 1 Get 1 offer',
            'items' => ($this->branch_key === request()->branch_key) ? CategoryResource::collection(Category::getCategories()->get()) : [],
            
            
            $this->mergeWhen($request->branch, [
                'branch_description' => $this->vendor_description,                
                'branch_address' => $this->branch_address,                
                'area_name' => $this->area_name,
                'city_name' => $this->city_name,
                'country_name' => $this->country_name,
                'rating' => $this->when($this->branch_id,BranchReview::getBranchReviews($this->branch_id)),
                'time_info' => $this->when(true, BranchTimeslot::timeInfo($this->branch_id)),
            ]),            
            // 'zone_radius' => $this->zone_radius,  /**  for testing */
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
