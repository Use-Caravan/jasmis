<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Requests\Frontend\RegisterRequest;
use App\Http\Requests\Frontend\LoginRequest;
use App\Http\Requests\Frontend\OTPRequest;
use App\Http\Requests\Frontend\ProfileUpdateRequest;
use App\{
    User,
    OTPTemp,
    UserCorporate
};
use App\Helpers\SendOTP;
use FileHelper;
use Auth;
use Hash;
use Socialite;
use Validator;
use Common;
use DB;



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
    
    public $user, $purpose = OTP_PURPOSE_CREATE_ACCOUNT;

   /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialGoogleRedirectToProvider()
    {         
        return Socialite::driver('google')->redirect(); 
    }
    
    public function socialFacebookRedirectToProvider()
    {         
        return Socialite::driver('facebook')->redirect(); 
    }
    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialGoogleHandleProviderCallback()
    {   
        $socialGoogleUser = Socialite::driver('google')->stateless()->user();
        $socialGoogleUserName = $socialGoogleUser->name;
        $explodeName = explode(' ',$socialGoogleUserName);
        $user = User::where(['login_type' => LOGIN_TYPE_GP,'social_token' => $socialGoogleUser->id])->first();
        if($user === null) {
            $user = User::where(['email' => $socialGoogleUser->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
            if($user === null) {
                $user = new User();
            }
            $user->login_type = LOGIN_TYPE_GP;
            $user->social_token = $socialGoogleUser->id;
            $user->first_name = isset($explodeName[0]) ? $explodeName[0] : '';
            $user->last_name = isset($explodeName[1]) ? $explodeName[1] : '';
            $user->email = $socialGoogleUser->email;
            $user->profile_image = $socialGoogleUser->avatar;
            $user->status = ITEM_ACTIVE;
        }  
        else {
            $user->profile_image = $socialGoogleUser->avatar;
        }
        $user->save();
        Auth::loginUsingId($user->user_id);
        return redirect()->route('frontend.index');
    }

    public function socialFacebookHandleProviderCallback()
    {   
        $socialFacebookUser = Socialite::driver('facebook')->stateless()->user();
        $socialFacebookUserName = $socialFacebookUser->name;
        $explodeName = explode(' ',$socialFacebookUserName);
        $user = User::where(['login_type' => LOGIN_TYPE_FB,'social_token' => $socialFacebookUser->id])->first();
        if($user === null) {
            $user = User::where(['email' => $socialFacebookUser->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
            if($user === null) {
                $user = new User();
            }
            $user->login_type = LOGIN_TYPE_FB;
            $user->social_token = $socialFacebookUser->id;
            $user->first_name = isset($explodeName[0]) ? $explodeName[0] : '';
            $user->last_name = isset($explodeName[1]) ? $explodeName[1] : '';
            $user->email = $socialFacebookUser->email;
            $user->profile_image = $socialFacebookUser->avatar;
            $user->status = ITEM_ACTIVE;
            
        } else {
            $user->profile_image = $socialFacebookUser->avatar;

        }
        $user->save();
        Auth::loginUsingId($user->user_id);
        return redirect()->route('frontend.index');
    }
   
    /**
     * Login
     *
     * @return view
     */
    public function signin(Request $request)
    {       
        $user = User::where('username',$request->username)->orWhere('email',$request->username)
        ->where(['user_type' => USER_TYPE_CUSTOMER])->first();
        
        if($user === null) {            
            $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Username or email does not match')];    
            goto error;
        }        
        if($user->status === ITEM_INACTIVE || $user->status == null) {
            $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.User is inactived by admin')];
            goto error;
        }
        if($request->username === null) {
            $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Email is Required')];
            goto error;
        }
        if($request->password === null) {
            $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Password is Required')];
            goto error;
        }
        
        if (
            Auth::guard(GUARD_USER)->attempt(['phone_number' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE]) ||
            Auth::guard(GUARD_USER)->attempt(['email' => $request->username, 'password' => $request->password, 'status' => ITEM_ACTIVE])
        ) {            
            if($user->otp_verified !== OTP_VERIFIED) {
                Auth::guard(GUARD_USER)->logout();
                $response = ['status' => OTP_UNVERIFIED,'data' => [ 'user_key' => $user->user_key ] ,'msg' => __('frontendmsg.You have to veriry OTP')];
            } else {

                if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER) {
                    $request->session()->put(LAST_AUTH_USER, Auth::guard(GUARD_USER)->user()->user_id);
                }
                $response = ['status' => AJAX_SUCCESS, 'msg' => __('frontendmsg.User Login Successfully')];
            }
        }        
        else {            
            $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Invalid username or password')];    
        }

        error:
        return response()->json($response); 
    }
    
    public function register(RegisterRequest $request,$len = '16')
    {     
        if($request->ajax()) {            
            $user = User::where(['email' => $request->email, 'user_type' => USER_TYPE_CUSTOMER])->first();
            if($user !== null) {
                return response()->json(['status' => EMAIL_EXISTS,'data' => $user->user_key,'msg' => __('frontendmsg.The email already exists')]); 
            }        
            $user = User::where(['phone_number' => (int)$request->phone_number, 'user_type' => USER_TYPE_CUSTOMER])->first();
            if($user !== null) {                 
                return response()->json(['status' => PHONE_NUMBER_EXISTS,'msg' => __('frontendmsg.The phone number already exists')]); 
            }
            if($user !== null) {
                if($user->otp_verified !== OTP_VERIFIED) {
                    return response()->json(['status' => OTP_UNVERIFIED,'data' => [ 'user_key' => $user->user_key ] ,'msg' => __('frontendmsg.You have to veriry OTP')]);
                }
            }
            $existsCardNumber = User::get();
            $user = new User();
            $user = $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $cardNumber = sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
            foreach ($existsCardNumber as $key => $value) {
                if($value->card_number = $cardNumber) {
                   $user->card_number = sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
                }
                else {
                    $user->card_number = $cardNumber;
                } 
            }
            $user->status = ITEM_ACTIVE;
            if($user->save()) {
                $response = ['status' => AJAX_SUCCESS, 'msg' => __('frontendmsg.User Register Successfully')];            
                $this->user = $user;
                
                $otp = $this->sendOTP();
                return $otp;
            }
            else {
                $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.something went wrong')];    
            }
            return response()->json($response);
        } 
    }


    /**
     * Send OTP
     */
    public function sendOTP()
    {   
        $otpPin = Common::generateOTP();
        $user = $this->user;
        if($user === null) {
            $user = User::findByKey(request('user_key'));
        }
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
            return response()->json(['status' => AJAX_FAIL, 'msg' => __('OTP not sent')]);
        } else */ {
            $otp->save();  
            $otpTemp = [
                    'otp_temp_key' => $otp->otp_temp_key,            
                    'user_key' => User::find($user->user_id)->user_key,
                    'otp' => $otp->otp
                ];
            return response()->json(['data' => $otpTemp,'status' => AJAX_SUCCESS,'msg' => __('frontendmsg.OTP has been sent')]);
        }                
    }

    /**
     * Verify OTP
     */
    public function verifyOTP(Request $request)
    {
        $otpTemp = OTPTemp::findByKey($request->otp_temp_key);
        if($otpTemp === null) {
            return response()->json(['status' => AJAX_FAIL, 'msg' => __('frontendmsg.OTP data not found')]);
        }
        if($otpTemp->status == OTP_VERIFIED) {
            return response()->json(['status' => AJAX_FAIL, 'msg' => __('frontendmsg.This OTP already verified')]);
        }   
        if($otpTemp->otp != $request->otp){
            return response()->json(['status' => AJAX_FAIL, 'msg' => __('frontendmsg.OTP mismatch')]);
        }
        
        $otpTemp->status = OTP_VERIFIED;
        $otpTemp->save();
        $user = User::find($otpTemp->user_id);
        $user->status = ITEM_ACTIVE;
        $user->otp_verified = OTP_VERIFIED;
        $user->otp_verified_at = date('Y-m-d H:i:s');
        $user->save();
        $this->user = $user;
        $this->authLogin();
        return response()->json(['status' => AJAX_SUCCESS, 'msg' => __('frontendmsg.OTP Verified')]);
    }

    public function authLogin()
    {
        return Auth::guard(GUARD_USER)->login($this->user);
    }

    public function corporateLogin(Request $request)
    {
        $user = User::where(['email' => $request->office_email,'user_type' => USER_TYPE_CORPORATES])->first();
        if($user === null) {
            $user = new User();
            $user->email = $request->office_email;
            $user->user_type = USER_TYPE_CORPORATES;
            $user->status = ITEM_ACTIVE;
            $user->save();
        }
        $model = UserCorporate::where(['office_email' => $request->office_email, 'is_booked' => 0])->first();
        if($model === null) {
            $model = new UserCorporate();   
        }
        $model = $model->fill($request->all());
        $model->status = ITEM_ACTIVE;
        if($model->company_logo !== null) {
            FileHelper::deleteFile($model->company_logo);
        }
        $model->company_logo = FileHelper::uploadFile($request->company_logo,CORPORATE_COMPANY_LOGO);
        $model->valid_upto = date('Y-m-d H:i:s', strtotime($request->valid_upto));
        $model->save();

        if(Auth::guard(GUARD_USER)->check()) {
            if(Auth::guard(GUARD_USER)->user()->user_type === USER_TYPE_CUSTOMER) {
                $request->session()->put(LAST_AUTH_USER, Auth::guard(GUARD_USER)->user()->user_id);
            }
        }

        Auth::guard(GUARD_USER)->login($user);
        return response()->json(['status' => HTTP_SUCCESS, 'redirect_url' => route('frontend.branch.index') ]);
    }


    /**
     * Logout
     * @return view
    */    
    public function signout(Request $request)
    {   
        $userType = Auth::guard(GUARD_USER)->user()->user_type;         
        Auth::guard(GUARD_USER)->logout();
        if ($userType === USER_TYPE_CORPORATES && $request->session()->has(LAST_AUTH_USER)) {
            $user = User::find($request->session()->get(LAST_AUTH_USER));        
            Auth::guard(GUARD_USER)->login($user);
            $request->session()->forget(LAST_AUTH_USER);
        }
        return redirect()->route('frontend.index');
    }  
}