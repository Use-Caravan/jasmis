<?php

namespace App\Http\Resources\Api\V1\Vendor;

use Illuminate\{
    Http\Resources\Json\JsonResource,
    Http\Request,
    Http\Response
};
use App\Api\{
    User,
    LoyaltyLevel,
    LoyaltyLevelLang    
};
use App\Vendor;
use App\VendorLang;
use FileHelper;
use Common;

class VendorResource extends JsonResource
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
            //'branch_id' => $this->branch_id,
            'user_key' => $this->when(true,function() {
                if(request()->user() === null) {
                    return ($this->vendor_key === null) ? $this->branch_user_key : $this->vendor_key;
                }
                return (request()->user() instanceof Vendor) ? $this->vendor_key : $this->branch_user_key;
            }),
            'username' => ($this->username === null) ? '' : $this->username,
            'email' => ($this->email === null) ? '' : $this->email,
            //'mobile_number' => (( request()->user() instanceof Vendor) ? ($this->mobile_number === null) ? '' : $this->mobile_number : ($this->contact_number === null) ? '' : $this->contact_number),
            'mobile_number' => ($this->mobile_number === null) ? '' : $this->mobile_number;// : ($this->contact_number === null) ? '' : $this->contact_number),        
            'vendor_name' => VendorLang::where('vendor_id',$this->vendor_id)->value('vendor_name'),
            'vendor_logo' => VendorLang::where('vendor_id',$this->vendor_id)->value('vendor_logo'),
            'vendor_address' => VendorLang::where('vendor_id',$this->vendor_id)->value('vendor_address'),
            'access_token' => ($this->access_token === null) ? $request->bearerToken() : $this->access_token,
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
