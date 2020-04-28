<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\WalletController as APIWalletController;
use Common;
use App\Transaction;

class WalletController extends Controller
{
    public function wallet(Request $request)
    {   
        $transaction = null;
        if($request->transaction_number !== null) {
            $transaction = Transaction::where('transaction_number',$request->transaction_number)->first();
        }
        return view('frontend.profile.wallet',compact('transaction'));
    }
    public function addMoney(Request $request)
    {
        if($request->ajax()) {
            request()->request->add([
                'is_web' => true
            ]);
            $response = Common::compressData((new APIWalletController)->addMoney());
            return response()->json($response);
       }
    }

     public function redeemPoint(Request $request)
    {  
        if($request->ajax()) {
            $response = Common::compressData((new APIWalletController)->redeemPoint());
            return response()->json($response);
       }
    }
}
