<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Api\OrderItem;
use App\Http\Resources\Api\V1\Vendor\OrderItemResource;
use App\Http\Resources\Api\V1\UserAddressResource;
use Common;
use App\Api\Order;
use App\Api\Branch;
use App\Api\BranchLang;
use App\Api\vendor;
use App\Api\vendorLang;
use App\Api\UserAddress;
use App\Api\AddressType;
use App\Api\AddressTypeLang;
use DB;

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
        return [
            'order_key' => $this->order_key, 
            'branch_key' => Branch::where('branch_id',$this->branch_id)->value('branch_key'),
            'branch_name' => BranchLang::where('branch_id',$this->branch_id)->value('branch_name'),
            'arabic_branch_name' => BranchLang::where('branch_id',$this->branch_id)->where('language_code','ar')->value('branch_name'),
            'vendor_key' => Vendor::where('vendor_id',$this->vendor_id)->value('vendor_key'),
            'vendor_name' => VendorLang::where('vendor_id',$this->vendor_id)->value('vendor_name'),
            'arabic_vendor_name' => VendorLang::where('vendor_id',$this->vendor_id)->where('language_code','ar')->value('vendor_name'),
            'delivery_street' => UserAddress::where('user_address_id',$this->user_address_id)->value('street'),
            'delivery_area' => UserAddress::where('user_address_id',$this->user_address_id)->value('area'),
            'customer_name' => $this->customer_name,            
            'order_number' => ($this->order_number === null) ? '' : "#".config('webconfig.app_inv_prefix').$this->order_number,            
            'order_datetime' => Common::renderDate($this->order_datetime),
            'payment_type' => (new Order)->paymentTypes($this->payment_type),
            'payment_status' => (new Order)->paymentStatus($this->payment_status),
            'order_type' => ($this->order_type === null) ? 0 : $this->order_type,
            'order_type_label' => ($this->order_type === null) ? "" :(new Order)->orderTypes($this->order_type),            
            'delivery_type' => ($this->delivery_type === null) ? 0 : $this->delivery_type,
            'delivery_type_label' => ($this->delivery_type === null) ? "" : (new Order)->deliveryTypes( $this->delivery_type),
            'delivery_datetime' => Common::renderDate($this->delivery_datetime),            
            'phone_number' => $this->user_phone_number,
            'order_status'  => $this->order_status,
            'order_status_label'  => ($this->order_status === null) ? "" :(new Order)->approvedStatus($this->order_status),            
            'status_color' => ($this->order_status === null) ? ORDER_DEFAULT_COLOR :(new Order)->approvedStatus($this->order_status,'color'),
            'delivery_address' => $this->when(true,function() {                
                $query = UserAddress::getList()
                ->addSelect( 
                    DB::raw("CONCAT_WS(', ',`address_line_one`,`address_line_two`,`landmark`,`company`) AS full_address")
                    )
                ->leftJoin(AddressType::tableName(),UserAddress::tableName().'.address_type_id',AddressType::tableName().'.address_type_id')
                ->where([
                    UserAddress::tableName().'.status' => ITEM_ACTIVE,
                ]);
                AddressTypeLang::selectTranslation($query);
                $query = $query->where('user_address_id',$this->user_address_id)->first();
                if($query === null) {
                    return '';
                }
                return $query->full_address;
                /* return new UserAddressResource($query); */
            }),
            'order_type' => $this->order_type,            
            'order_message' => ($this->order_message === null) ? '' : $this->order_message,                      
            'tax' => Common::currency($this->tax),
            'service_tax' => Common::currency($this->service_tax),
            'delivery_fee' => Common::currency($this->delivery_fee),
            'item_total' => Common::currency($this->item_total),
            'voucher_offer_value' => ($this->voucher_id !== null) ? Common::currency($this->voucher_offer_value) : Common::currency(0),
            'order_total' => Common::currency($this->order_total),  
            'order_total_flat' => round($this->order_total,2),  
            $this->mergeWhen( ($request->order_key !== null), [
                'items' =>  $this->when($request->order_key, function() {
                    $orderItems = OrderItem::getOrderItems($this->order_id);                    
                    return OrderItemResource::collection($orderItems);
                }),
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
