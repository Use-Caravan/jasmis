<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Common;
use App\Helpers\Curl;


class SendOTP
{    
    public static $instance;
    public $url;
    //public $to = "97336938478";  
    public $message = "message from caravan";  
    
    public static function instance($refresh = false)
    {
        if (self::$instance === null || $refresh) {            
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setReciver($to)
    {   
        $this->to = $to;
        return $this;
    }

    public function send($message = "")
    {           
        $this->message = $message;
        $username = config('webconfig.sms_gateway_username');
        $password = config('webconfig.sms_gateway_password');                  
        $from = urlencode(config('webconfig.sms_sender_id'));
        $to = urlencode($this->to);
        $message = urlencode($this->message);
        $parameter = "user=$username&pass=$password&from=$from&to=$to&text=$message";
        // $url = "https://esms.etisalcom.net:9443/smsportal/services/SpHttp/sendsms?".$parameter;
        $url ='http://smshorizon.co.in/api/sendsms.php?user=Jigno&apikey=Rpiptu4k1O3LDs8EHwST&mobile='.$to.'&message='.$message.'&senderid=iJIGNO&type=txt';

        $data = Curl::instance()->setUrl($url)->send([],true);
        if($data === HTTP_SUCCESS ){
            return true;
        }
        return false;
    }
}