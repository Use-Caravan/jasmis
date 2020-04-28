<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;

use App\Http\Requests\Frontend\{
    DriverRegisterRequest,
    ContactRequest,
    NewsletterRequest
};
use App\Http\Controllers\Api\V1\{
    UserController as APIUserController,    
    OfferController as APIOfferController,
    BranchController as APIBranchController
};
use App\{
    AddressType,
    Cms,
    Faq,
    Configuration,
    Deliveryboy,
    Enquiry,
    NewsletterSubscriber,
    UserAddress,
    Order,
    Branch,
    Vendor,
    Banner,
    User
};
use Auth;
use Common;
use DB;
use Session;
use Hash;
use Validator;


class HomeController extends Controller
{
    public function index()
    { 
        request()->request->add([
            'display_in_home' => 1,
            'is_home_banner' => 1
        ]);
        $offer = (new APIOfferController)->getOffers();
        $offerItems = Common::getData($offer);
        $orderTypes = (new Order)->orderTypes();
        $branch = (new APIBranchController)->branchByVendor();
        $branch = Common::getData($branch);
        //$branch = Branch::getList()->where('approved_status',BRANCH_APPROVED_STATUS_APPROVED)->get();
        $bannerImage = Banner::getBannerImage();                
        return view('frontend.index',compact('orderTypes','branch','offerItems','bannerImage'));
    }              

    public function cms($page)
    { 
        $model = Cms::getList()->where(['slug' => $page,'status' => ITEM_ACTIVE])->first();
        if($model === null){
            return redirect()->route('frontend.index');
        }
        return view('frontend.cms',compact('model'));
    }
    
    public function help()
    { 
        return view('frontend.help');
    }

   public function loyaltyPoints()
    {   
        $loyaltyLevelName = Common::compressData((new APIUserController)->userDetails());
        return view('frontend.profile.loyalty-points',compact('loyaltyLevelName'));
    }   

    public function faq()
    {
        $model = Faq::getList()->get();
        return view('frontend.faq',compact('model'));
    }

    public function driverRegister(DriverRegisterRequest $request)
    {   
        if($request->Ajax()) {
            $driver = new Deliveryboy();
            $driver = $driver->fill($request->except('terms'));
            $driver->password = Hash::make($request->password);
            if($driver->save()) {
                $response = ['status' => AJAX_SUCCESS, 'msg' => __('frontendmsg.Driver Register Successfully')];
            }
            else {
                $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Something Went Wrong')];
            }
            return response()->json($response);
        }
    }
    

    public function newsletter(NewsletterRequest $request)
    {  
     if($request->Ajax()) {
             $validator = Validator::make($request->all(),[
            
                'email' => 'required|unique:newsletter_subscriber,email',
            ]);
            if($validator->fails()) {
                return response()->json(['status' => AJAX_FAIL, 'msg' => __('frontendmsg.You are already subscribed with this email')]);
            }  
            $newsletter = new NewsletterSubscriber();
            $newsletter = $newsletter->fill($request->all());
            if($newsletter->save()) {
               $response = ['status' => AJAX_SUCCESS, 'msg' => __('frontendmsg.Newletter add Successfully')];
            }
            else {
                $response = ['status' => AJAX_FAIL, 'msg' => __('frontendmsg.Something Went Wrong')];
            }
            return response()->json($response);
        }   
    }

    public function changeLanguage(Request $request)
    {       
        if($request->ajax()) {
            $language = ($request->language != '') ? $request->language : 'en';        
            Session::put(SESSION_LANGUAGE, $language);
            Session::save();
            if(Auth::user()) {
                $user = User::find(Auth::guard(GUARD_USER)->user()->user_id);
                $user->default_language = $language;
                $user->save();   
            }
            /* Configuration::updateOrCreate(['configuration_name' => 'language_code'],['configuration_name' => 'language_code', 'configuration_value' => $language]); */
            return response()->json($language);
        }
    }

    
    public function sendSms()
    {   
        
        $username = config('webconfig.sms_gateway_username');
        $password = config('webconfig.sms_gateway_password');
        $from = urlencode(config('webconfig.sms_sender_id'));
        $to = urlencode('9865412302');
        $message = urlencode('test message from caravan');
        $Parameter = "user=$username&pass=$password&from=$from&to=$to&text=$message";
        $url = "https://esms.etisalcom.net:9443/smsportal/services/SpHttp/sendsms?".$Parameter;
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER,  ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_POST, false);        
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $response;

    }     
}
