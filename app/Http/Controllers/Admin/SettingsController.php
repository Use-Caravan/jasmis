<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\{
        Controllers\Controller\Admin,
        Requests\Admin\ConfigurationRequest,
        Requests\Admin\MailConfigRequest,
        Requests\Admin\SocialMediaConfigRequest,
        Requests\Admin\CurrencyConfigRequest
    };
use App\{
        Mail\TestMail,
        Helpers\Curl,
        Helpers\SendOTP,
        Configuration,        
        AdminUser,
        Vendor,
        BranchUser,
        Language
    };
use App;
use Auth;
use Common;
use Session;
use Storage;
use FileHelper;

/**
 * @Title("Configuration Management")
 */
class SettingsController extends Controller
{
    public function changeLangugage(Request $request)
    {   
        $defaultLanguage = Language::where(['status' => ITEM_ACTIVE,'is_default' => 1])->first(); 
        if($defaultLanguage === null) {
            $languageCode = 'en';
        } else {
            $languageCode = $defaultLanguage->language_code;
        }
        $language = ($request->language != '') ? $request->language : $languageCode;
        /* Session::put(SESSION_LANGUAGE, $language);
        Session::save(); */
        if(Auth::guard(APP_GUARD)->check()) {
            if(auth()->guard(APP_GUARD)->user() instanceof AdminUser) {
                $authUser = AdminUser::find(auth()->guard(APP_GUARD)->user()->admin_user_id);
                $authUser->default_language = $language;
                $authUser->save();  
            } else if(auth()->guard(APP_GUARD)->user() instanceof Vendor) {                
                $authUser = Vendor::find(auth()->guard(APP_GUARD)->user()->vendor_id);
                $authUser->default_language = $language;
                $authUser->save();  
            } else if(auth()->guard(APP_GUARD)->user() instanceof BranchUser) {                
                $authUser = BranchUser::find(auth()->guard(APP_GUARD)->user()->branch_user_id);
                $authUser->default_language = $language;
                $authUser->save();
            }
        }        
        /* Configuration::updateOrCreate(['configuration_name' => 'language_code'],['configuration_name' => 'language_code', 'configuration_value' => $language]);
        Common::log("Language Change","Language changed to $language",$authUser); */
        return redirect()->back();
    }

    /**
     * @Title("App Settings")
     */
    public function appSettings(Request $request)
    {        
        $model = new Configuration();        
        return view('admin.settings.app_settings',compact('model'));
    }  
    
    /**
     * @Assoc("appSettings")
     */
    public function saveAppSettings(ConfigurationRequest $request)
    {   
        foreach($request->except('_token') as $key => $value){
            if ($request->hasFile($key)) {
                $exists = Configuration::where(['configuration_name' => $key])->first();
                if($exists !== null){
                    Storage::delete($exists->configuration_value);    
                }
                if($key == 'app_logo' || $key == 'app_favicon') {
                    $path = APP_LOGO_PATH;
                } else if($key == 'home_banner') {
                    $path = APP_HOME__BANNER_PATH;
                }
                $value = FileHelper::uploadFile($request->{$key},$path);
            }            
            $flight = Configuration::updateOrCreate(['configuration_name' => $key],['configuration_name' => $key, 'configuration_value' => $value]);
        }
        $deliveryboyConfiguration = $this->saveDeliveryboyConfigurations();
        if($deliveryboyConfiguration) {
            Common::log("Appsettings Save","Appsettings has been saved",new Configuration);
            return redirect()->route('admin-app-settings')->with('success',__('admincrud.App configuration updated') );
        }
        return redirect()->route('admin-app-settings')->with('error', 'Deliveryboy configuration not saved');
        
    }

    /**
     * @Title("Mail Settings")
     */
    public function mailSettings(Request $request)
    {        
        $model = new Configuration();        
        return view('admin.settings.email_settings',compact('model'));
    }

    /**
     * @Title("Social Media Settings")
     */
    public function socialmediaSettings()
    {
        $model = new Configuration();
        return view('admin.settings.socialmedia_settings',compact('model'));
    }    

    /**
     * @Title("Currency Settings")
     */
    public function currencySettings()
    {
        $model = new Configuration();        
        return view('admin.settings.currency_settings',compact('model'));
    }

    /**
     * @Title("Currency Settings")
     */
    public function corporateSettings()
    {
        $model = new Configuration();        
        return view('admin.settings.corporate_settings',compact('model'));
    }

    /**
     * @Title("SMS Settings")
     */
    public function smsSettings()
    {
        $model = new Configuration();        
        return view('admin.settings.sms_settings');
    }

    /**
     * @Title("Loyalty Point Settings")
     */
    public function loyaltypointSettings()
    {
        $model = new Configuration();        
        return view('admin.settings.loyalty_point_settings');
    }


    /**
     * @Title("Delivery boy Settings")
     */
    public function deliveryBoySettings()
    {
        $model = new Configuration();        
        return view('admin.settings.deliveryboy_settings');
    }

    /**
     * @Assoc("appSettings")
     */
    public function saveSettings(Request $request)
    {                
        switch($request->config_name) {
            case CONFIG_APP:                
                $message = __('admincrud.App configuration updated');
                goto update;
            break;
            case CONFIG_MAIL:                
                $message = __('admincrud.Mail configuration updated');
                goto update;
            break;
            case CONFIG_SMS:                
                $message = __('admincrud.SMS configuration updated');
                goto update;
            break;
            case CONFIG_SOCIAL_MEDIA:                
                $message = __('admincrud.Social Media configuration updated');
                goto update;
            break;
            case CONFIG_CURRENCY:                
                $message = __('admincrud.Currency configuration updated');
                goto update;
            break;
            case CONFIG_LOYALTY_POINT:                
                $message = __('admincrud.Loyalty Point configuration updated');
                goto update;
            break;
            case DELIVERY_BOY:                
                $message = __('admincrud.Delivery boy configuration updated');
                goto update;
            break;
            default:                
                $message = __('admincrud.App configuration updated');
                goto update;
            break;
        }
    
        update:
        foreach($request->except('_token','config_name') as $key => $value) {
            $flight = Configuration::updateOrCreate(['configuration_name' => $key],['configuration_name' => $key, 'configuration_value' => $value]);            
        } 
        $deliveryboyConfiguration = $this->saveDeliveryboyConfigurations();
        if($deliveryboyConfiguration) {
            Common::log("Settings Save","Settings has been saved",new Configuration);       
            return redirect()->back()->with('success', $message);    
        }
        return redirect()->back()->with('error', 'Deliveryboy configuration not saved');                
    } 

    /**
     * @Title("Send Test Mail")
     */
    public function testMail()
    {
        try {
            $sendMail = Mail::to(config('webconfig.app_email'))->send(new TestMail());            
        } catch (\Exception $ex) {            
            throw $ex; 
            return redirect()->route('admin-mail-settings')->with('error',__('admincrud.Mail not sent') );
        }        
        return redirect()->route('admin-mail-settings')->with('success',__('admincrud.Test mail has sent') );
    }
    

    public function testSMS()
    {   
        try {
            $receiver = urlencode(config('webconfig.app_contact_number'));
            $message = "message from caravan";            
            $sendOTPUrl = SendOTP::instance()->setReciver($receiver)->send($message);
        } catch (\Exception $ex) {              
            throw $ex; 
            return redirect()->route('admin-sms-settings')->with('error',__('admincrud.Sms not sent') );
        }        
        return redirect()->route('admin-sms-settings')->with('success',__('admincrud.Test sms has sent') );
    } 


    public function saveDeliveryboyConfigurations()
    {        
        $companyDetails = [
            'app_setting' => [
                'app_name' => config('webconfig.app_name'),
                'app_email' => config('webconfig.app_email'),
                'app_contact_no' => config('webconfig.app_contact_number'),
                'app_contact_address' => config('webconfig.app_address'),
                'app_radius' => config('webconfig.request_radius'),
                'app_latitude' => config('webconfig.app_latitude'),
                'app_longitude' => config('webconfig.app_longitude'),
                'app_currency_code' => config('webconfig.currency_code'),
                'app_logo_path' => config('webconfig.app_logo')
            ],
            'sms_setting' => [
                'is_sms_enabled' => config('webconfig.is_sms_enabled'),
                'sms_account_id' => config('webconfig.sms_gateway_username'),
                'sms_auth_token' => config('webconfig.sms_gateway_password')
            ],
            'smtp_setting' => [
                'is_smtp_enabled' => config('webconfig.is_smtp_enabled'),
                'smtp_host' => config('webconfig.smtp_host'),
                'smtp_port' => config('webconfig.port'),
                'smtp_username' => config('webconfig.smtp_username'),
                'smtp_password' => config('webconfig.smtp_password'),
                'smtp_encryption' => config('webconfig.encryption')
            ],
            'theme' => [
                'app_primary_color' => config('webconfig.app_primary_color')
            ],
            'one_signal' => [
                'one_signal_app_id' => config('webconfig.one_signal_app_id'),
                'one_signal_auth_id' => config('webconfig.one_signal_auth_id'),
                'vendor_one_signal_app_id' => config('webconfig.vendor_one_signal_app_id'),
                'vendor_one_signal_auth_id' => config('webconfig.vendor_one_signal_auth_id'),
                'deliveryboy_one_signal_app_id' => config('webconfig.deliveryboy_one_signal_app_id'),
                'deliveryboy_one_signal_auth_id' => config('webconfig.deliveryboy_one_signal_auth_id') 
            ],
            'backend_setting' => [
                /* 'url' => 'http://caravan.duceapps.com' */
                'url' => url("/")
            ],
            'driver_per_order' => ''
        ];
        $url = config('webconfig.deliveryboy_url')."/api/v1/company?company_id=".config('webconfig.company_id');
        $postData = json_encode($companyDetails);                
        $data = Curl::instance()->action('POST')->setUrl($url)->send($postData);
        $response = json_decode($data,true);
        
        if($response['status'] == HTTP_SUCCESS) {
            return true;            
        } else {
            return false;
        }
    }
}
