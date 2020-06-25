<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\OrderItem;
use App\Api\Order;
use App\Api\VendorLang;
use App\Api\BranchReview;
use App\Http\Resources\Api\V1\OrderItemResource;
use Common;
use FileHelper;

class OrderResource extends JsonResource
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
        $ratings = BranchReview::where('vendor_id',$this->vendor_id)->where('branch_id',$this->branch_id)->where('user_id',$this->user_id)
                        ->where('status',ITEM_ACTIVE)
                        ->where('approved_status',REVIEW_APPROVED_STATUS_APPROVED)->first();
						
	   $user_rating = BranchReview::where('vendor_id',$this->vendor_id)->where('branch_id',$this->branch_id)->
                       where('user_id',$this->user_id)->first();
        
        return [
            'order_key' => $this->order_key,            
            'order_number' => ($this->order_number === null) ? '' : "#".config('webconfig.app_inv_prefix').$this->order_number,
            'claim_corporate_offer_booking' => $this->claim_corporate_offer_booking,
            'corporate_voucher_code' => $this->corporate_voucher_code,
            'order_total' => Common::currency($this->order_total),
            'corder_total' => $this->order_total,
            'csub_total' => $this->item_total,
            'order_datetime' => Common::renderDate($this->order_datetime),
            'vendor_logo' => FileHelper::loadImage(Vendorlang::where('vendor_id',$this->vendor_id)->pluck('vendor_logo')),
            'branch_name' => $this->branch_name,                        
            'branch_key' => $this->branch_key, 
            'rating'     =>  ($user_rating == null) ? "null" : 'rated',  			
            'branch_rating' => ($ratings == null) ? "" : $ratings->rating,                           
            'branch_review' => ($ratings == null) ? "" : $ratings->review,                           
            'color_code' => ($this->color_code === null) ? '' : '#'.$this->color_code,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->when(true,function()
                {
                    return [
                        'name' => __('apimsg.Total'),
                        'price' => Common::currency($this->order_total),
                        'color_code' => PAYMENT_GRAND_TOTOAL_COLOR,
                        'is_bold' => IS_BOLD,
                        'is_italic' => IS_ITALIC,
                        'is_line' => IS_LINE,
                    ];
                }),
            'order_type' => ($this->order_type === null) ? "" : $this->order_type,    
            'status' => ($this->order_status === null) ? "" :(new Order)->approvedStatus($this->order_status),
            'order_status_value' => ($this->order_status === null) ? "" : $this->order_status,
            'status_color' => ($this->order_status === null) ? ORDER_DEFAULT_COLOR : (new Order)->approvedStatus($this->order_status,'color'),            
            'branch_latitude' => ($this->branch_latitude === null) ? '' : $this->branch_latitude,
            'branch_longitude' => ($this->branch_longitude === null) ? '' : $this->branch_longitude,
            'user_latitude' => ($this->user_latitude === null) ? '' : $this->user_latitude,
            'user_longitude' => ($this->user_longitude === null) ? '' : $this->user_longitude,
            'item_namelist' => OrderItem::getOrderItems($this->order_id)->pluck('item_name'),
            $this->mergeWhen($request->order_key, [                

                'items' =>  $this->when($request->order_key, function() {                    
                    $orderItems = OrderItem::getOrderItems($this->order_id);
                    return OrderItemResource::collection($orderItems);
                }),
                'payment_details' => $this->when(true, function(){
                    $paymentDetails = [
                        [ 
                            'name' => __('apimsg.Sub total'), 
                            'price' => Common::currency($this->item_total),
                            'color_code' => PAYMENT_SUB_TOTOAL_COLOR,
                            'is_bold' => IS_BOLD,
                            'is_italic' => IS_ITALIC,
                            'is_line' => IS_LINE,
                        ],
                        [
                            'name' => __('apimsg.Delivery Fee'), 
                            'price' => Common::currency($this->delivery_fee),
                            'color_code' => PAYMENT_DELIVERY_FEE_COLOR,
                            'is_bold' => IS_BOLD,
                            'is_italic' => IS_ITALIC,
                            'is_line' => IS_LINE,                            
                        ],
                        [
                            'name' => __('apimsg.VAT',['percent' => $this->tax_percent]),
                            'price' => Common::currency($this->tax),
                            'color_code' => PAYMENT_VAR_TAX_COLOR,
                            'is_bold' => IS_BOLD,
                            'is_italic' => IS_ITALIC,
                            'is_line' => IS_LINE, 
                        ],                        
                    ];
                    if($this->service_tax !== null && $this->service_tax > 0) {
                        $service_tax = [
                            'name' => __('apimsg.Service Tax',[ 'percent' => $this->service_tax_percent]),
                            'price' => Common::currency($this->service_tax), 
                            'color_code' => PAYMENT_SERVICE_TAX_COLOR,
                            'is_bold' => IS_BOLD,
                            'is_italic' => IS_ITALIC,
                            'is_line' => IS_LINE,
                        ];
                        array_push($paymentDetails,$service_tax);
                    }
                    if($this->voucher_offer_value !== null && $this->voucher_offer_value != 0 && $this->voucher_offer_value != '') {
                        array_push($paymentDetails, [
                            'name' => __('apimsg.Coupon Offer'), 
                            'price' => Common::currency($this->voucher_offer_value),
                            'color_code' => PAYMENT_COUPON_FEE_COLOR,
                            'is_bold' => IS_BOLD,
                            'is_italic' => IS_ITALIC,
                            'is_line' => IS_LINE,
                        ]);                    
                    }                        
                    return $paymentDetails;
                })
            ]),            
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
