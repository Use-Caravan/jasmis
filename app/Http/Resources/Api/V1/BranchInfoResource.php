<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\{
    Api\BranchReview,
    Api\Vendor,
    Http\Resources\Api\V1\BranchRatingResource
};

class BranchInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $vendor = new Vendor();
        $branchReview = new BranchReview();
        return [
            'branch_name' => $this->branch_name,
            'cuisine_name' => $this->cuisinenames,
            'branch_description' => $this->vendor_description,
            'preparation_time' => $this->preparation_time,
            'delivery_time' => $this->delivery_time,
            'pickup_time' => $this->pickup_time,
            'min_order_value' => $this->min_order_value,
            'payment_option' => $this->when($this->payment_option,$vendor->paymentOptions($this->payment_option)),
            'area_name' => $this->area_name,
            'city_name' => $this->city_name,
            'country_name' => $this->country_name,
            'rating' => $this->when($this->branch_id,function() use($branchReview)
            {                
                $branchReviews = $branchReview::getBranchReviews()->where($branchReview->getTable().".branch_id", $this->branch_id)->get();
                return BranchRatingResource::collection($branchReviews);
            }),
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
