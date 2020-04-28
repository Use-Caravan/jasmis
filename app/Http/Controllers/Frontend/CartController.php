<?php
namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\CartController as APICartController;
use App\Http\Controllers\Api\V1\OrderController as APIOrderController;
use App\{
    Cart,
    CartItem,
    Branch,
    Item
};
use App;
use Common;
use DB;
use Validator;



class CartController extends Controller
{    
    
    public function userCart()
    {        
        /* $branch = Branch::findByKey(request()->branch_key);
        $existCart = Cart::select('branch_id')->where(['user_id' => request()->user()->user_id])->first();
        if($existCart !== null) {
            if($existCart->branch_id !== $branch->branch_id) {
                return response()->json(['status' => 202, 'message' => __('You have choose another branch.If you submit existing cart will be clear!')]);
            }
        }     */    
        
        $response = Common::compressData((new APICartController)->userCart());        
        $message = $response->message;
        if($response->status === HTTP_SUCCESS) {
            $rawData = json_decode(request()->getContent(), true);
            $branchDetails = Branch::findByKey($rawData['branch_key']);
            $response = (new APICartController)->getCart();            
            $cartDetails = Common::getData($response);
            $htmlOP = view('frontend.branch.branch-cart',compact('cartDetails','branchDetails'))->render();
            $result = Common::compressData($response);
            $result->design = $htmlOP;
            $result->checkout_url = route('frontend.checkout',[$branchDetails->branch_slug ]);
            $result->branch_key = $branchDetails->branch_key;
            $result->message = $message;
            return response()->json($result);
        }      
    }

    
    public function updateQuantity(Request $request)
    {   
        if($request->ajax()) {              
            
            $response = Common::compressData((new APICartController)->updateQuantity());
            $message = $response->message;
            if($response->status === HTTP_SUCCESS) {
                
                $branch_key = request()->branch_key;
                $branchDetails = Branch::findByKey(request()->branch_key);                
                if($request->checkout == true) {
                                                                                
                    $data = (new APIOrderController())->calculateData();        
                    $response = Common::compressData($data);
                    if($response->status == HTTP_SUCCESS) {
                        $cartItem = Common::getData($data);
                        if($cartItem === null || empty($cartItem)) {
                            $response->status = HTTP_UNPROCESSABLE;
                            return response()->json($response);
                        }
                        $checkoutCart = view('frontend.branch.checkout-cart',compact('cartItem','branchDetails'))->render();
                        $checkoutPayment = view('frontend.branch.checkout-payment',compact('cartItem','branchDetails'))->render();
                        $response->checkout_cart = $checkoutCart;
                        $response->checkout_payment = $checkoutPayment;
                        $response->branch_url = route('frontend.branch.show',[$branchDetails->branch_slug]);
                        return response()->json($response);
                    }
                } else {
                    $response = (new APICartController)->getCart();            
                    $cartDetails = Common::getData($response);                                
                    $htmlOP = view('frontend.branch.branch-cart',compact('cartDetails','branchDetails'))->render();
                    $result = Common::compressData($response);
                    $result->design = $htmlOP;
                    $result->checkout_url = route('frontend.checkout',[$branchDetails->branch_slug ]);
                    $result->branch_key = $branchDetails->branch_key;
                    $result->message = $message;    
                    return response()->json($result);
                }                                
            }
        }        
    }

    public function clearCart()
    {   
        $branch_slug = Branch::select('branch_slug')->where(['branch_key' => request()->branch_key])->first();
        $response = Common::compressData((new APICartController)->clearCart());
        $message = $response->message;
        if($response->status === HTTP_SUCCESS) {
            return redirect()->route('frontend.branch.show',[ $branch_slug->branch_slug]);
        }
        return redirect('/');
    }
}
