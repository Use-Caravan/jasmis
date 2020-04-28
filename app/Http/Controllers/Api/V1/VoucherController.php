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

    
    public function getVouchers()
    {
        $vouchers = Voucher::getBranchVouchers();
        $modelVoucher = VoucherResource::collection($vouchers->get());
        $this->setMessage(__('apimsg.Voucher List'));
        return $this->asJson($modelVoucher);
    }
   
}
