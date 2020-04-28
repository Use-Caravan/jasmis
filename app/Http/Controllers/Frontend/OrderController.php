<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\CartController as APICartController;
use App\Http\Controllers\Api\V1\UserAddressController as APIUserAddressController;
use App\Http\Controllers\Api\V1\BranchController as APIBranchController;
use App\Http\Controllers\Api\V1\OrderController as APIOrderController;
use App\{
    AddressType,
    Branch,
    BranchLang,
    Cart,
    Order,
    UserCorporate,
    UserAddress   
};
use App;
use Auth;
use Common;
use Session;


class OrderController extends Controller
{

    public function checkout($branch_key, Request $request)
    {        
        
        $branchDetails = Branch::getList()->select('branch_key','branch_name','branch_slug')->where(['branch_slug' => $branch_key])->first();
        request()->request->add([
            'branch_key' => $branchDetails->branch_key,            
        ]);        
           
        if($request->session()->has('coupon_code')) {
            request()->request->add([
                'coupon_code' => session('coupon_code')
            ]);
        }
        if($request->session()->has('corporate_voucher')) {
            request()->request->add([ 'corporate_voucher' => true ]);
        }
        if($request->session()->has('corporate_voucher_code')) {
            request()->request->add([ 'corporate_voucher_code' => $request->session()->get('corporate_voucher_code') ]);
        }

        $cartDetails = Cart::where(['user_id' => auth()->guard(GUARD_USER)->user()->user_id])->first();
        if($cartDetails === null) {
            return redirect()->route('frontend.branch.show',[$branchDetails->branch_slug]);
        }
        
        $response = (new APIOrderController())->calculateData();     
        
        $cartItem = Common::compressData($response);       

        /** If there is not items in cart it will redirect to branch details */
        if($cartItem->status === EXPECTATION_FAILED) {
            /** Voucher code condition not satisfied means, get data without coupon code */
            $request->session()->forget('coupon_code');
            request()->request->remove('coupon_code');            
            $response = (new APIOrderController())->calculateData();        
            $cartItem = Common::compressData($response);
            //return response()->json(['status' => EXPECTATION_FAILED,'message' => "user deacitvate"]);
        }
        
        $cartItem = $cartItem->data;        
        $userAddress = Common::getData((new APIUserAddressController())->index());
        $paymentTypes = (new Order())->paymentTypes();
        $orderTypes = (new Order())->orderTypes();
        $deliveryTypes = (new Order())->deliveryTypes();
        $addressTypes = AddressType::getAddressType();        
        $corporateUser = null;
        if(Auth::guard(GUARD_USER)->user()->user_type ===  USER_TYPE_CORPORATES) {
            $corporateUser = UserCorporate::where(['office_email' => Auth::guard(GUARD_USER)->user()->email, 'is_booked' => 0])->orderBy('user_corporate_id','desc')->first();
        }        
        
        return view('frontend.checkout.index',compact('branchDetails','cartItem','userAddress','addressTypes','orderTypes','paymentTypes','deliveryTypes','corporateUser'));
    }

    public function placeOrder(Request $request)
    {   
        
        request()->request->add([ 'delivery_time' => request()->delivery_time.":00" ]);
        if($request->session()->has('coupon_code')) {
            request()->request->add([
                'coupon_code' => session('coupon_code')
            ]);        
        }
        if($request->session()->has('corporate_voucher')) {
            request()->request->add([ 'corporate_voucher' => true ]);
        }
        if($request->session()->has('corporate_voucher_code')) {
            request()->request->add([ 'corporate_voucher_code' => $request->session()->get('corporate_voucher_code') ]);
        }

        request()->request->add([
            'is_web' => true
        ]);
        
        $response = (new APIOrderController())->placeOrder();
        $cartItem = Common::compressData($response);
        if($cartItem->status === HTTP_SUCCESS) {
            $request->session()->forget('coupon_code');
            $request->session()->forget('user_address_key');
            $cartItem->url = route('frontend.confirmation',['order_key' => $cartItem->data->order_key]);
 
            if( (int)$cartItem->data->payment_mode === PAYMENT_OPTION_ONLINE ) {
                
                $cartItem->url = $cartItem->data->payment_url;
            }
        }
        $request->session()->forget('corporate_voucher');
        $request->session()->forget('corporate_voucher_code');
        return response()->json($cartItem);
    }

    public function calculateData(Request $request)
    {                        
        if(request()->delivery_time !== null) {
            request()->request->add([ 'delivery_time' => request()->delivery_time.":00" ]);
        }                  

        $data = (new APIOrderController())->calculateData();        
        $response = Common::compressData($data);
        if($response->status == HTTP_SUCCESS) {
            if(request()->coupon_code !== null) {
                $request->session()->put('coupon_code',request()->coupon_code);
            }
            
            $cartItem = Common::getData($data);
            if($cartItem === null || empty($cartItem)) {
                $response->status = HTTP_UNPROCESSABLE;
                return response()->json($response);
            }            
            $branchDetails = Branch::findByKey($request->branch_key);
            
            $checkoutCart = view('frontend.branch.checkout-cart',compact('cartItem','branchDetails'))->render();
            $checkoutPayment = view('frontend.branch.checkout-payment',compact('cartItem','branchDetails'))->render();
            $response->checkout_cart = $checkoutCart;
            $response->checkout_payment = $checkoutPayment;
        }
        return response()->json($response);
    }

    public function confirmation(Request $request)
    { 
        $order = Order::select([
                Order::tableName().".*",
                "BL.branch_logo"
            ])
            ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",'=',Order::tableName().".branch_id")
            ->where(Order::tableName().".order_key",$request->order_key);
            
        BranchLang::selectTranslation($order);
        $order = $order->first(); 
        return view('frontend.checkout.confirmation',compact('order'));
    }

    public function orderFailed(Request $request)
    { 
        $order = Order::select([
                Order::tableName().".*",
                "BL.branch_logo"
            ])
            ->leftJoin(Branch::tableName(),Branch::tableName().".branch_id",'=',Order::tableName().".branch_id")
            ->where(Order::tableName().".order_key",$request->order_key);
            
        BranchLang::selectTranslation($order);
        $order = $order->first();                
        return view('frontend.checkout.failed',compact('order'));
    }

    public function index(Request $request)
    {   
        $orderModel = new Order();
        $order = (new APIOrderController)->index();
        $orderDetails = Common::getData($order);
        return view('frontend.profile.orders',compact('orderDetails','orderModel'));
    }

    public function show($id,Request $request)
    {                   
        $response = Common::compressData((new APIOrderController)->show($id));
        
        if($request->ajax()){ 
            $viewOrder = view('frontend.profile.order_view',compact('response'))->render();
            return response()->json(['status' => HTTP_SUCCESS,'msg' => __("frontendmsg.Orders are fetched"),'viewOrder' => $viewOrder]);
        }
        return response()->json($response);
    }  
    
    public function reOrders(Request $request)
    {   
        $reorder = Common::compressData((new APIOrderController)->reOrder());        
        $order = Order::findByKey($request->order_key);
        if($order === null) {
            return back()->with('error',__('frontendmsg.Order items not found') );
        }
        $branch = Branch::find($order->branch_id);   
        if($branch === null || $order->branch_id === null) {
            return back()->with('error',__('frontendmsg.Branch not found') );
        }
        if($branch->status === ITEM_INACTIVE) {
            return back()->with('error',__('frontendmsg.Branch is not actived currently') );
        }
        if($reorder->status === HTTP_SUCCESS) {
            return redirect()->route('frontend.checkout',[$branch->branch_slug]);
        }
        else {
            return back()->with('error',__('frontendmsg.Order items not found') );
        }
    }

}
