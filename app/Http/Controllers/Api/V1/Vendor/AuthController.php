<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Http\Controllers\Api\V1\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Auth\AuthenticationException;
use App\Model\Authenticator;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Api\BranchUser;
use App\Api\Vendor;
use App\Api\Branch;
use Validator;
use Session;

use Common;
use Input;
use Auth;
use DB;

class AuthController extends Controller
{
    /**
     * @var Authenticator
    */
    private $authenticator, $user;

    public function __construct(Authenticator $authenticator)
    {        
        $this->authenticator = $authenticator;
    }


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

    /**
     * Login
     *
     * @return view
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'  => 'required|email',
            'password' => 'required|min:6',
            'device_type'  => 'required|numeric|in:'.DEVICE_TYPE_ANDROID.','.DEVICE_TYPE_IOS.','.DEVICE_TYPE_WINDOWS,
            'device_token' => 'required_unless:device_type,'.DEVICE_TYPE_WEB,
        ]);
        if($validator->fails()) {            
            return $this->validateError($validator->errors());
        } 
        $guard = GUARD_VENDOR_API_PROVIDER;
        $vendor = Vendor::where('email',$request->email)->first();        
        if($vendor === null) {            
            $branchUser = BranchUser::where('email',$request->email)->first();
            $branch = Branch::where('contact_email',$request->email)->first();                        
            if($branchUser !== null && $branch !== null) {
                if($branch->status !== ITEM_ACTIVE) {                    
                    return $this->commonError(__('apimsg.User account is not activated'));
                }
                $guard = GUARD_OUTLET_API_PROVIDER;
            } else {
                return $this->commonError(__('apimsg.Email Not found'));
            }            
        } else {
            if($vendor->status !== ITEM_ACTIVE) {
                return $this->commonError(__('apimsg.User account is not activated'));
            }
        }        
        if (! $user = $this->authenticator->attempt($request->email,$request->password,$guard)) {
            return $this->unAuthorised(); 
        }
        
        if ($user) {                        

            //$accessToken = $user->createToken('My Token')->accessToken;            
            /** Login */            
            $tokenResult =  $user->createToken('VendorAuthToken');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            /** Login */
            if($guard === GUARD_VENDOR_API_PROVIDER) {
                $vendor->device_type = $request->device_type;
                $vendor->device_token = $request->device_token;
                $vendor->save();
            } else {
                $branchUser->device_type = $request->device_type;
                $branchUser->device_token = $request->device_token;
                $branchUser->save();
            }
            
            $user->access_token = $tokenResult->accessToken;
            $data = new VendorResource($user);
            $this->setMessage(__('apimsg.Authentication successfully completed'));
            return $this->asJson($data);
        }
        else{
            return $this->unAuthorised(); 
        }
    }        

    /**
     * Logout
     * @return view
    */    
    public function logout(Request $request)
    {                   
        $accessToken = request()->user()->token();
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
        $this->setMessage(__('apimsg.Vendor has logged out'));
        return $this->asJson();
    }

}
