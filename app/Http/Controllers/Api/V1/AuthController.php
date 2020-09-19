<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\AuthenticationException;
use App\Model\Authenticator;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\OTPResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Api\User;
use App\Api\OTPTemp;
use App\Helpers\SendOTP;
use Carbon\Carbon;
use Input;
use Validator;
use FileHelper;
use Auth;
use DB;
use Common;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public $user, $authenticator, $purpose = OTP_PURPOSE_CREATE_ACCOUNT;
    
    /**
     * @var Authenticator
    */    

    public function __construct(Authenticator $authenticator)
    {        
        $this->authenticator = $authenticator;
    }



    public function login(Request $request)
    {     
        $user = new User();        
        $validator = Validator::make($request->all(),[
            'email'  => 'required|email|exists:user,email',
            'password' => 'required|min:6',
            'device_type'  => 'required|numeric|in:'.DEVICE_TYPE_ANDROID.','.DEVICE_TYPE_IOS.','.DEVICE_TYPE_WINDOWS,
            'device_token' => 'required_unless:device_type,'.DEVICE_TYPE_WEB,
        ]);
        
        if($validator->fails()) {    
            return $this->validateError($validator->errors());
        }         
        $user = User::where(['email' => $request->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
        if($user === null) {
            return $this->commonError( __('apimsg.Email Not found') );
        }   
             
        if($user->otp_verified !== OTP_VERIFIED) {
            $this->setData(new UserResource($user));
            return $this->otpVeficationRequired(__('apimsg.You have to verifiy OTP') );
        }
        if($user->status !== ITEM_ACTIVE) {
            return $this->commonError( __('apimsg.User account is not activated') );
        }
        
        

        if ($user = $this->authenticator->attempt($request->email,$request->password, GUARD_USER_API_PROVIDER)) {
            

        /* if(
            Auth::attempt(['email' => request('email'), 'password' => request('password')])
        ) {
            $user = Auth::user(); */

            $user = $user->fill($request->except('password'));
            $user->save();
            $this->user = $user;
            $this->setMessage( __('apimsg.Login Successful') );
            return $this->makeAuth();
        }
        else{
            return $this->unAuthorised();         
        }
    }

    /**
     * Create Authentication 
     */
    public function makeAuth($socialAuth = false)
    {
        $user = $this->user;        
        /** Login */
        $tokenResult =  $user->createToken('UserAuthToken');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        /** Login */
        $user->access_token = $tokenResult->accessToken;
        $data = new UserResource($user);        
        return $this->asJson($data);
    }

    /**
     * Social Authentication
     */
    public function socialAuth(Request $request)
    {
        $user = new User();        
        $validator = Validator::make($request->all(),[
            'device_type'  => 'required|numeric|in:'.DEVICE_TYPE_ANDROID.','.DEVICE_TYPE_IOS.','.DEVICE_TYPE_WINDOWS,
            'device_token' => 'required_unless:device_type,'.DEVICE_TYPE_WEB,
            'login_type'  => 'required|numeric|in:'.LOGIN_TYPE_GP.','.LOGIN_TYPE_FB.','.LOGIN_TYPE_APPLE,
            'social_token' => 'required_unless:login_type,'.LOGIN_TYPE_APP,
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'email' => 'nullable|email',
        ]);
        if($validator->fails()) {            
            return $this->validateError($validator->errors());
        } 
        
        $user = User::where(['login_type' => $request->login_type,'social_token' => $request->social_token])->first();
        if($user === null) {
            if($request->email !== null) {
                $user = User::where(['email' => $request->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
                if($user === null) {
                    $user = new User();
                }                
            } else {
                $user = new User();
            }
        }
        $user->login_type = $request->login_type;
        $user->social_token = $request->social_token;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->status = ITEM_ACTIVE;
        $user->device_token = $request->device_token;

        $len = '16';
        $existsCardNumber = User::get();
        $cardNumber = sprintf("%0".$len."d", mt_rand(1, (int)str_pad("", $len,"9")));
        foreach ($existsCardNumber as $key => $value) {
            if($value->card_number = $cardNumber) {
               $user->card_number = sprintf("%0".$len."d", mt_rand(1, (int)str_pad("", $len,"9")));
            }
            else {
                $user->card_number = $cardNumber;
            } 
        }

        $user->save();
        $this->user = $user;
        $this->setMessage( __('apimsg.Login Successful') );
        return $this->makeAuth();
    }

    /**
     * Create new account
     */
    public function register(Request $request)
    {               
        $user = new User();        
        $validator = Validator::make($request->all(),
            [
                'first_name'  => 'required',
                'last_name'  => 'required',
                'email'     => 'required|email',
                'phone_number' => 'required|numeric|digits_between:8,15',
                'password'  => 'required|min:6',                    
                'accept_terms_conditions' => 'required',
                'device_type'  => 'required|numeric|in:'.DEVICE_TYPE_ANDROID.','.DEVICE_TYPE_IOS.','.DEVICE_TYPE_WINDOWS,
                'device_token' => 'required_unless:device_type,'.DEVICE_TYPE_WEB,
            ]);
        if($validator->fails()) {            
            return $this->validateError($validator->errors());
        }                
        $errorMsg = '';
        $user = User::where(['email' => $request->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
        if($user !== null) {            
            if($user->otp_verified !== OTP_VERIFIED) {
                $this->setData(new UserResource($user));
                return $this->otpVeficationRequired( __('apimsg.Email already exists. You have to verifiy OTP') );
            }
            $errorMsg = __('apimsg.Email already exists');
            goto error;
        }
        
        $user = User::where(['phone_number' => (int)$request->phone_number, 'user_type' => USER_TYPE_CUSTOMER])->first();
        if($user !== null) {
            if($user->otp_verified !== OTP_VERIFIED) {
                $this->setData(new UserResource($user));
                return $this->otpVeficationRequired( __('apimsg.Phone Number already exists. You have to verifiy OTP') );
            }
            $errorMsg = __('apimsg.Phone Number already exists');
            goto error;
        }
        $len = '16';
        $existsCardNumber = User::get();
        $user = new User();
        $user = $user->fill($request->all());        
        $user->password = bcrypt($request->password);
        $cardNumber = sprintf("%0".$len."d", mt_rand(1, (int)str_pad("", $len,"9")));
        foreach ($existsCardNumber as $key => $value) {
            if($value->card_number = $cardNumber) {
               $user->card_number = sprintf("%0".$len."d", mt_rand(1, (int)str_pad("", $len,"9")));
            }
            else {
                $user->card_number = $cardNumber;
            } 
        }
        $user->save();        
        $this->user = $user;

        $otp = $this->sendOTP();
        return $otp;                    

        error:
        return $this->commonError($errorMsg);
    }

    

    /**
     * Logout
     *
     * @return view
    */    
    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();
        $accessToken->revoke();
        /* Another Methods
            Method 1 :
                DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);        
            Method 2 :
            $value = $request->bearerToken();
            if ($value) {
                $id = (new Parser())->parse($value)->getHeader('jti');
                $revoked = DB::table('oauth_access_tokens')->where('id', '=', $id)->update(['revoked' => true]);
            }
        */
        $this->setMessage( __('apimsg.User has logged out') );
        return $this->asJson();
    }

    /**
     * Send OTP
     */
    public function sendOTP()
    {
        $otpPin = Common::generateOTP();
        $user = $this->user;
        if($user === null) {
            if( request('user_key') !== null )
                $user = User::findByKey(request('user_key'));
            else if( request('phone_number') !== null )
                $user = User::where(['phone_number' => request('phone_number'), 'user_type' => USER_TYPE_CUSTOMER])->first();
        }

        if($user !== null) {
            $otp = OTPTemp::where(['user_id' => $user->user_id,'otp_purpose' => $this->purpose])->where('status','!=',OTP_VERIFIED)->first();
            if($otp === null) {            
                $otp = new OTPTemp();
                $otp = $otp->fill([
                    'user_id' => $user->user_id,
                    'otp' => $otpPin,
                    'otp_purpose' => $this->purpose,
                    'status' => OTP_UNVERIFIED,
                ]);
            } else {
                $otp->otp = $otpPin;
            }

            $message = "Your One Time Password is $otpPin";
           
            $sendOTP = SendOTP::instance()->setReciver($user->phone_number)->send($message);
            /* if(!$sendOTP) {
                return $this->commonError( __('apimsg.OTP not sent') );
            } else  { */
                $otp->save();        
                $otpTemp = new OTPResource($otp);
                $this->setMessage( __('apimsg.OTP has been sent') );
                $this->setData($otpTemp);
                return $this->asJson();
           /* } */
        }
    }    

    /**
     * Verify OTP
     */
    public function verifyOTP(Request $request)
    {
        $otpTemp = OTPTemp::findByKey($request->otp_temp_key);
        if($otpTemp === null) {
            return $this->commonError( __('apimsg.OTP data not found') );
        }
        if($otpTemp->status == OTP_VERIFIED) {
            return $this->commonError( __('apimsg.This OTP already verified') ) ;
        }   
        if($otpTemp->otp != $request->otp){
            return $this->commonError( __('apimsg.OTP mismatch') );
        }
        
        $otpTemp->status = OTP_VERIFIED;
        $otpTemp->save();
        $user = User::find($otpTemp->user_id);
        $user->status = ITEM_ACTIVE;
        $user->device_type = $request->device_type;
        $user->device_token = $request->device_token;
        $user->otp_verified = OTP_VERIFIED;
        $user->otp_verified_at = date('Y-m-d H:i:s');
        $user->save();
        $this->user = $user;
        $this->setMessage( __('apimsg.OTP has been verified') );
        return $this->makeAuth();
    }
}
