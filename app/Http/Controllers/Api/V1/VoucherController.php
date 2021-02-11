<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\VoucherResource,
    Resources\Api\V1\BranchResource
};
use Illuminate\Http\Response;
use App\Api\{
    Branch,
    Voucher
};
use Validator;
use DB;
use App;
use Auth;
use App\Api\User;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVoucherBranches()
    {        
        request()->request->add([
            'voucher_branch' => true
        ]);
        $modelVoucher = BranchResource::collection(Branch::getBranches()->get());
        $this->setMessage(__('apimsg.Voucher List'));            
        return $this->asJson($modelVoucher);
    }

    
    public function getVouchers($filter_value)
    {
        $userID = Auth::user()->user_id;
        $vouchers = Voucher::getBranchVouchers();
        $vouchers = $vouchers->addSelect([
            DB::raw(" (SELECT COUNT(VU.voucher_id) FROM voucher_usage AS VU where VU.beneficiary_type = ".VOUCHER_APPLY_PROMO_USERS." and VU.voucher_id = ".Voucher::tableName().".voucher_id "." and VU.beneficiary_id = ".$userID."  and VU.status = ".ITEM_ACTIVE.") as usage_count")
        ]);

        if( $filter_value == "active" ) //Active coupons user can use ( Usage count not exceed limit of use )   
            $vouchers->havingRaw('usage_count < limit_of_use');       
        else if( $filter_value == "used" ) //Used  coupons user can't use ( Usage count exceed or equal to limit of use )
            $vouchers->havingRaw('usage_count >= limit_of_use');        

        $modelVoucher = VoucherResource::collection($vouchers->get());
        $this->setMessage(__('apimsg.Voucher List'));
        return $this->asJson($modelVoucher);
    }
   
}
