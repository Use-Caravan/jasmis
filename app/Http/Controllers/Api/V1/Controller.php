<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Language;
use App\Api\Branch;
use App\Api\Cart;
use App\Api\CartItem;
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
            $cart = 0;
            $branch_key = null;
        }else{
            $cart = CartItem::where(['cart_id' => $cart_details->cart_id])->count();
            $branch_key = Branch::where('branch_id',$cart_details->branch_id)->value('branch_key');
        }

        $response = [
            'status' => $this->status, 
            'message' => $this->message,
            'time'=> time(),
            'cart_quantity' => $cart,
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
