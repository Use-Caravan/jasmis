<?php

namespace App\Helpers;

use Session;
use Illuminate\Http\Request;
use App\{
    Language,
    AdminUser,
    ActivityLog,
    Branch,
    Cart,
    CartItem
};
use App;
use Auth;
use DB;
use Config;



class Common
{
	/**
     * @param object $model
     * @param int $source
     *
     * @return array
     */
	  public static function updateTranslationAttribute($model, $primaryKey)
	  {
		  return true;
    }

    /**
     * Method to generate an random string of length 16 by default
     *
     * @param int $length
     * @param bool $escSpecialChar
     * @return bool|string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\Exception
     */
    public static function generateRandomString($table, $column, $length = 16)
    {
        $unique = false;
        do{
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $bytes = '';
            for ($i = 0; $i < $length; $i++) {
                $bytes .= $characters[rand(0, $charactersLength - 1)];
            }                            
            $bytes = preg_replace('/[^A-Za-z0-9 ]/', '', $bytes);            
            $randomStr = substr($bytes, 0, $length);            
            $count = DB::table($table)->where($column, '=', $randomStr)->count();
            if( $count == 0){                
                $unique = true;
            }
        }
        while(!$unique);        
        return $randomStr;
    }
    
    public static function getLanguages($keypair = true)
    {             
        $languages = Language::where('status', ITEM_ACTIVE)->get()->toArray();
        return array_column($languages,'language_name', 'language_code');
    }

    /**
    * Generate Random string
    * @param int $length defualt 5
    * @return string 
    */
    public static function randomString($length = 5)
    {        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;     
    }

    /**
     * @param string $logName
     * @param string $description
     * @param object $performedOn
     * @param integer $adminUserId
     * @param string $adminUsername
     */
    public static function log($logName, $description, $performedOn, $adminUserId = null,  $adminUsername  = null)
    {           
        $user = Auth::guard(APP_GUARD)->user();
        switch (APP_GUARD) {
            case GUARD_ADMIN:                                            
                $causer_id = $user->admin_id;
                $causer_name = $user->username;
                break;
            case GUARD_VENDOR:
                $causer_id = $user->vendor_id;
                $causer_name = $user->username;
                break;
            case GUARD_OUTLET:
                $causer_id = $user->branch_id;
                $causer_name = $user->email;
                break;
        }
        
        $data = [
            'log_name'  =>  $logName,
            'description' => $description,
            'causer_type'  => get_class(new AdminUser()),
            'causer_id' => $causer_id,
            'causer_name' => $causer_name,
            'subject_type' => get_class($performedOn),
            'properties'   => json_encode(Common::getBrowser()),
        ];
        $model = new ActivityLog();
        $model = $model->fill($data);
        return $model->save();
    }


    /** Get Browser details */
    public static function getBrowser() {

        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/Chrome/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Opera/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
                ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }

        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }

        return array(
            'User agent' => $u_agent,
            'Browser name' => $bname,
            'Version' => $version,
            'Platform' => $platform,
            'pattern' => $pattern,
            'Client IP' => Common::get_client_ip()
        );
    }

    /** Get client IP address */
    public static function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    /**Get Status  */
    public function status($status = null)  
    {
        $list = [
            1 => __('admincommon.Active'),
            0 => __('admincommon.Inactive'),
        ];
        return ($status != null) ? $list[$status] : $list;
    }


    /**Get Popular Status  */
    public function popular($popular = null)  
    {
        $list = [
            1 => __('admincommon.Active'),
            0 => __('admincommon.Inactive'),
        ];
        return ($popular != null) ? $list[$popular] : $list;
    }

    /**Get quickbuy Status  */
    public function quickbuy($quickbuy = null)  
    {
        $list = [
            1 => __('admincommon.Active'),
            0 => __('admincommon.Inactive'),
        ];
        return ($quickbuy != null) ? $list[$quickbuy] : $list;
    }

    /**Get new item Status  */
    public function newitem($newitem = null)  
    {
        $list = [
            1 => __('admincommon.Active'),
            0 => __('admincommon.Inactive'),
        ];
        return ($newitem != null) ? $list[$newitem] : $list;
    }

    /**
     * @param array $data associative array ( Key is constant value is text)
     * @return string associate array to string ( Key Join )
     */
    public static function assoImplodeKey($data)
    {
        if(!is_array($data)) {
            return '';
        }
        return implode(',', array_map(
            function ($v, $k) { 
                return $k; 
            },
            $data,
            array_keys($data)
        ));        
    }


    /**
     * @param int/float $amount
     * @return string amount format with currency
     */
    public static function currency($amount = 0)
    {        
        $decimalPlace = config('webconfig.currency_decimal');
        $currencySymbol = config('webconfig.currency_symbol');
        $currencyPosition = config('webconfig.currency_position');
        $amount = self::round($amount, $decimalPlace);
        return ($currencyPosition == CURRENCY_LEFT) ? $currencySymbol." ".$amount : $amount." ".$currencySymbol;
    }


    /**
     * @param int/float $value
     * @return float with decimal places 
     */
    public static function round($value = 0, $decimals = 2)
    {        
        $value = (float)$value;        
        return number_format($value, $decimals, '.', '');
    }


    public static function gender($gender = null)
    {
        $types = [
            MALE => __('Male'),
            FEMALE => __('Female'),
        ];
        return ($gender === null) ? $types : $types[$gender];
    }

    public static function generateOTP()
    {
        $i = 0; //counter
        $pin = ""; //our default pin is blank
        $digits = 4;
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    public static function compressData($Object)
    {
        return $Object->getData();
    }
    public static function getData($Object)
    {   
        $properties = self::compressData($Object);
        return ($properties->status === HTTP_SUCCESS && property_exists($properties, 'data')) ?  $properties->data : [];
    }

    public static function getMessage($Object)
    {
        $properties = self::compressData($Object);
        return (property_exists($properties, 'message')) ?  $properties->message : '';
    }
    
    
    public static function renderDate($date = null, $format = null)
    {
        if($date === null) {
            $date = date('Y-m-d H:i:s');
        }        
        if($format === null) {
            $format = config('webconfig.app_date_format');
        }        
        //$date = str_replace(':','-',$date);
        return date($format, strtotime($date));        
    }


    public static function cartCount($userID)
    {                 
        $cart = Cart::where(['user_id' => $userID])->first();
        if($cart === null) {
            return ['branch_key' => '', 'cart_count' => 0];
        } else {                        
            $branch = Branch::find($cart->branch_id); 
            $cartCount =  CartItem::where(['cart_id' => $cart->cart_id])->count();
            return ['branch_key' => $branch->branch_key,'branch_slug' => $branch->branch_slug, 'cart_count' => $cartCount];
        }        
    }

    public static function serverName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public static function generateVoucherNumber()
    {
        $unique = false;
        do{
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $bytes = '';
            for ($i = 0; $i < 8 ; $i++) {
                $bytes .= $characters[rand(0, $charactersLength - 1)];
            }                            
            $bytes = preg_replace('/[^A-Za-z0-9 ]/', '', $bytes);            
            $randomStr = substr($bytes, 0, 8);            
            $count = DB::table('corporate_voucher')->where('voucher_number', '=', $randomStr)->count();
            if( $count == 0){                
                $unique = true;
            }
        }
        while(!$unique);        
        return $randomStr;
    }
}
