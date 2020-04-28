<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;
use App\BranchTimeslot as CommonBranchTimeslot;
use App\Http\Resources\Api\V1\TimeInfoResource;
use DB;

class BranchTimeslot extends CommonBranchTimeslot
{    
    public static function timeInfo($branchId)
    {        
        $deliveryTimeSlot = self::where([
            'branch_id' => $branchId,
            'timeslot_type' => ORDER_TYPE_DELIVERY,
            'status' => ITEM_ACTIVE
        ])->orderBy('day_no','asc')->get();
        $pickupTimeSlot = self::where([
            'branch_id' => $branchId,
            'timeslot_type' => ORDER_TYPE_PICKUP_DINEIN,
            'status' => ITEM_ACTIVE
        ])->orderBy('day_no','asc')->get();
        return [
            'delivery' => TimeInfoResource::collection($deliveryTimeSlot),
            'pickup'   => TimeInfoResource::collection($pickupTimeSlot)
        ];
    }
}
