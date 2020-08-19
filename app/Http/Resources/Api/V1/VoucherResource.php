<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Api\Voucher;
use App\Api\Branch;
use FileHelper;
use Common;

class VoucherResource extends JsonResource
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
            'branch_key' => $this->branch_key,
            'branch_slug' => $this->branch_slug,
            'promo_code' => $this->promo_code,
            'discount_type' => $this->discount_type,
            'limit_of_use' => $this->limit_of_use,
            'max_redeem_amount'    => $this->max_redeem_amount,
            'offer_value'    => $this->value,
            'app_type'    => $this->app_type,
            'vendor_logo'    => FileHelper::loadImage($this->vendor_logo),
            'min_order_value' => $this->min_order_value,
            'expiry_date' => $this->expiry_date,            
            'offer_title' => $this->when(true, function() {
                if($this->discount_type === VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                    return "Get ".Common::currency($this->value)." off on all orders";
                } else {
                    return "Get $this->value% off on all orders";
                }
            }),
            'offer_expiry_msg' => $this->when(true, function() {
                return "Offer is valid until ".Common::renderDate($this->expiry_date);
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
    public function with($request)
    {
        return [
            'status' => Response::HTTP_OK,
            'time' => strtotime(date('Y-m-d H:i:s')),
        ];
    }
}