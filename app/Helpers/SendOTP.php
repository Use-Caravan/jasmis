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
        //$url ='http://smshorizon.co.in/api/sendsms.php?user=Jigno&apikey=Rpiptu4k1O3LDs8EHwST&mobile='.$to.'&message='.$message.'&senderid=iJIGNO&type=txt';
        $url = "https://esms.etisalcom.net:9443/smsportal/services/SpHttp/sendsms?".$parameter;
        //echo $url;exit;
        /*$url = "https://esms.etisalcom.net:9443/smsportal/services/SpHttp/sendsms?user=jasmis&pass=J@sm1s123&from=CARAVAN&to=33539186&text=API+test+message+for+Jasmis&at=2018-10-22T13:00:00&url=http%3A%2F%2Fwww.example.com%2Fyourpage%3Fsender%3D%25from%26receiver%3D%25to%26dlrtime%3D%25time%26status%3D%25status&fid=A2018";*/

        //$data = Curl::instance()->setUrl($url)->send([],true);
        $data = Curl::instance()->callAPI('GET', $url, false);
        /*$response = json_decode($get_data, true);
        $errors = $response['response']['errors'];
        $data = $response['response']['data'][0];*/
        //echo $data;exit;
        //if($data === HTTP_SUCCESS ){
        if( $data ){
            return true;
        }
        return false;
    }
}