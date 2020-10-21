<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Language;
use App\Api\Branch;
use App\Api\Cart;
use App\Api\CartItem;
use App\Api\Item;
use App\Api\Vendor;
use Common;// no
use App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $langCode = Language::where('language_code',request()->server('HTTP_ACCEPT_LANGUAGE'))->first();                
        if($langCode !== null) {
            if(request()->server('HTTP_ACCEPT_LANGUAGE') !== null && request()->server('HTTP_ACCEPT_LANGUAGE') !== ''){
                App::setLocale(  request()->server('HTTP_ACCEPT_LANGUAGE') );
            }
        }        
    }


    protected $message;
    protected $errors;
    protected $status;
    protected $data;
    protected $time;

    /**
     * @var double $cart 
     */
    protected $cart = 0;

    /**
     * This is for only web frontend purpose only
     */
    protected $error_for = '';    
    

    /**
     * @param array $result 
     * @return data with success 
     */ 
    public function asJson($result = null)
    {  
        $this->status = HTTP_SUCCESS;
        if($result !== null) {
            $this->data = $result;
        }
        return $this->prepareResponse();
    }

    /**
     * @param array $errors
     * @param boolean $return if you don't want to return with validate error send boolean (false)
     * @return errors with fail
     */ 
    public function validateError($errors = null, $return = true)
    {
        $this->status = HTTP_UNPROCESSABLE;
        if( $errors != null && is_string($errors) ) {

            // $response['errors'][] = $errors;
            $this->message = $errors;

        } elseif( $errors != null && is_object($errors) ) {
            $errorMsg = '';
            $count = count($errors->all());
            
            foreach ($errors->all() as $key => $error) {
                if($key+1 == $count) {
                    $errorMsg .= $error;
                } else {
                    $errorMsg .= $error.",";
                }                
            }
            // $this->errors = $errorMsg;
            $this->message = $errorMsg;

        } elseif( $errors != null && is_array($errors) ) {
            $errorMsg = '';
            foreach ($errors as $key => $error) {
                if(count($errors) == $key+1) {
                    $errorMsg .= $error;
                } else {
                    $errorMsg .= $error.",";
                }                
            }
            //$this->errors = $errorMsg;
            $this->message = $errorMsg;
        }
        if($return == true) {
            return $this->prepareResponse();
        }        
    }
    /**
     * Set common message
     */
    public function setMessage($message = '')
    {
        $this->message = $message;
    }

    /**
     * @param string $message
     * @return expectation failed 
     */
    public function commonError($message = '')
    {                
        $this->status = EXPECTATION_FAILED;
        $this->message = $message;
        return $this->prepareResponse();
    }

    /**
     * @param string $message
     * @return expectation failed 
     */
    public function errorFor($errorFor) 
    {
        $this->error_for = $errorFor;
    }

    /**
     * @param string $message
     * @return expectation failed 
     */
    public function Error($message = '')
    {       
        $this->status = EXPECTATION_FAILED;
        $this->message = $message;
        return $this->prepareResponse();
    }


    /**
     * @param string $message
     * @return expectation failed 
     */
    public function otpVeficationRequired($message = '')
    {       
        $this->status = OTP_VERFICATION_REQUIRED;
        $this->message = $message;
        return $this->prepareResponse();
    }

    /**
     * @param string $message
     * @return Unauthorised error
     */
    public function unAuthorised()
    {
        $this->status = UNAUTHORISED;
        $this->message = 'Invalid login credentials';
        return $this->prepareResponse(false);
    }
    

    public function setCart()
    {               
        if( (!auth()->guard(GUARD_USER_API)->check()) && (!auth()->guard(GUARD_USER)->check())) {
            return $this->cart = 0;
        } else {
            if(auth()->guard(GUARD_USER_API)->check()) {                               
                $userID = request()->user(GUARD_USER_API)->user_id;                
            }
            if(auth()->guard(GUARD_USER)->check()) {
                $userID = request()->user(GUARD_USER)->user_id;
            }                       
            // $branchKey = null;            
            // if(request()->wantsJson()) {                
            //     if(request()->headers->has('Branch-Key')) {
            //         $branchKey = request()->headers->get('Branch-Key');
            //     } else if(session('Branch-Key') !== null || session('Branch-Key') !== '') {
            //         $branchKey = session('Branch-Key');
            //     } else {
            //         return $this->cart = 0;
            //     }
            // } else {
            //     if(session('Branch-Key') === null || session('Branch-Key') === '') {                    
            //         return $this->cart = 0;
            //     }
            //     $branchKey = session('Branch-Key');
            // }            
            // if($branchKey !== null) {
            //     $branch = Branch::findByKey($branchKey);                
            //     if($branch === null) {
            //         return $this->cart = 0;
            //     } else {
                    
            //         $cart = Cart::where(['user_id' => $userID, 'branch_id' => $branch->branch_id])->first();
                    
            //         if($cart === null) {
            //             return $this->cart = 0;
            //         } else {
            //             return $this->cart =  CartItem::where(['cart_id' => $cart->cart_id])->count();
            //         }
            //     }
            // }

            
            

            
            return $this->cart = $userID;
            
        }
    }

    public function prepareResponse()
    {         
        
       $userID = $this->setCart();

       $cart_details = Cart::where(['user_id' => $userID, 'deleted_at' => NULL])->first();


       if(!$cart_details) {
            $cart_qty = 0;
            $branch_key = null;
            $totalCheckouAmount = 0;
        }else{
            $cart_qty = CartItem::where(['cart_id' => $cart_details->cart_id])->count();

            /* get cart price logic start */

            $branchDetails = Branch::select([
                Vendor::tableName().'.*',
                Branch::tableName().'.*',
            ])
                ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",'=',Vendor::tableName().'.vendor_id')
                ->where([
                    Branch::tableName().'.status' => ITEM_ACTIVE,
                    Vendor::tableName().'.status' => ITEM_ACTIVE,
                    Branch::tableName().'.branch_id' => $cart_details->branch_id,
                ])
                ->whereNull(Branch::tableName().".deleted_at")
                ->whereNull(Vendor::tableName().".deleted_at")
                ->first();

            $branch_key = isset( $branchDetails->branch_key ) ? $branchDetails->branch_key : null;
            
            $cartItem = CartItem::where('cart_id',$cart_details->cart_id)->get();
        
            $itemArray['items'] = [];
            foreach($cartItem as $key => $value) {            
                $item = Item::find($value->item_id);
                if($item === null) {
                    $getThisItem = CartItem::where(['cart_id' => $cart_details->cart_id,'item_id' => $value->item_id])->first();
                    if($getThisItem !== null) {
                        $getThisItem->delete();
                        
                    }
                    continue;
                }
                $itemArray['items'][] = [
                    'cart_item_key' => $value->cart_item_key,
                    'item_key' => $item->item_key,
                    'quantity' => $value->quantity,
                    'ingrdient_groups' =>  json_decode($value->ingredients,true),
                ];
            }     
            

            $items = (new OrderController())->itemCheckoutItemData($itemArray);


            if($items['status'] === false) {
                $totalCheckouAmount = 0;
            }
            
            $totalCheckouAmount = 0;
            $cart = isset( $items['data'] ) ? (new OrderController())->dataFormat(['items' => $items['data']]) : array(); 


            $cart['items'] = isset( $cart['items'] ) ? $cart['items'] : array();

            if( count($cart['items']) <= 0) {
               $totalCheckouAmount = 0; 
            } else {

                $itemSubtotal = 0;
                foreach($cart['items'] as $key => $value) {
                    $itemSubtotal += $value['subtotal'];
                }


                /** Vat tax amount */
                $vatAmount = ($itemSubtotal * $branchDetails->tax) / 100;
                         

                /** Service tax amount */
                $serviceTaxAmount = 0;
                if($branchDetails->service_tax !== null && $branchDetails->service_tax > 0) {
                    
                   $serviceTaxAmount = ($itemSubtotal * $branchDetails->service_tax) / 100;
                   
                }

                /** Total Cost */
                $totalCheckouAmount = $itemSubtotal +$vatAmount + $serviceTaxAmount;
              
            }
            
            
            /* get cart price logic end */
        }
        
        $response = [
            'status' => $this->status, 
            'message' => $this->message,
            'time'=> time(),
            'cart_quantity' => $cart_qty,
            'cart_price' => number_format($totalCheckouAmount,3),
            'branch_key' => $branch_key,
            'system' => [
                'error_for' => $this->error_for,
                'currency_symbol' => config('webconfig.currency_symbol')
            ]
        ];        

        if($this->data !== null) {
            $response['data'] = $this->data;
        }
        // if($this->errors !== null) {
        //     $response['errors'] = $this->errors;
        // }        
        return response()->json($response, $this->status);
    }    

    public function setData($data)
    {
        $this->data = $data;
    }
}
