<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\OfferController as APIOfferController;
use App\Http\Controllers\Api\V1\VoucherController as APIVoucherController;
use App\Helpers\Common;
use App\CorporateOffer;
use Auth;

class OfferController extends Controller
{
    public function offers()
    {        
        $offer = (new APIOfferController)->getOffers();
        request()->request->add([
            'app_type' => APP_TYPE_WEB
        ]);
        $voucherBranches = (new APIVoucherController)->getVoucherBranches();
        $offerItems = Common::getData($offer);
        $voucherBranches = Common::getData($voucherBranches);
        return view('frontend.offers.index',compact('offerItems','voucherBranches'));
    }

    public function getBranchVouchers()
    {
        if(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CUSTOMER) {
            $vouchers = (new APIVoucherController)->getVouchers();
            $vouchers = Common::getData($vouchers);
            if($vouchers == null) {
                return response()->json(['status' => EXPECTATION_FAILED,'msg' => __('frontendmsg.No offers found')]);
            }
            $vocher_for = 1; 
            $view = view('frontend.offers.branch-vouchers',compact('vouchers','vocher_for'))->render();
        }

        if(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CORPORATES) {
            $vouchers = CorporateOffer::getList()->where('status', ITEM_ACTIVE)->get();
        }        
        $vocher_for = 2;
        $view = view('frontend.offers.branch-vouchers',compact('vouchers','vocher_for'))->render();

        return response()->json(['status' => HTTP_SUCCESS, 'data' => $view]);
    }
}
