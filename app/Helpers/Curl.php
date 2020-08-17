<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Common;

/**
 * Class Curl
 * @package App\Helpers
 *
 * @author Aravindan R <aravindan.r@technoduce.com>
 */
class Curl
{
    public static $instance;

    /**
     * @var string Method name
     */
    public $method = 'GET';

    /**
     * @var string content type of curl
     */
    public $contentType = 'application/json';

    /**
     * @var string language code of header
     */
    public $lang = "en";
    
    /**
     * @var array curl send data
     */
    public $data = [];

    /**
     * @var string 
     */
    public $url;    

    /**
     * @var int http status code of curl response
     */
    public $httpcode = HTTP_SUCCESS;
    
    public static function instance($refresh = false)
    {   
        if (self::$instance === null || $refresh) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $method  Curl send method
     */
    public function action($method)
    {        
        $this->method = $method;
        return $this;
    }

    /**
     * @param string $url Curl action url
     */
    public function setUrl($url) 
    { 
        $this->url = $url;
        return $this;
    }


    /** 
     * @param string $type Content type for Curl Header
     */
    public function setContentType($type)
    {
        $this->contentType = $type;
        return $this;
    }


    /** 
     * @param string $type accept language for Curl Header
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /** 
     * @return array send curl headers
     */
    public function header()
    {        
        return [
            'Content-Type: '.$this->contentType,
            'Authorization: '.config('webconfig.auth_token'),
            'Accept-Language: '.$this->lang,
            'company-code: '.config('webconfig.credimaxpay_company_code'),
        ];
    }


    public function getStatusCode()
    {
        return $this->httpcode;
    }


    /**
     * @param array $data to send data
     * @param boolean $httpStatus ( If you want status code only should be send true)
     */
    public function send($data = [],$httpStatus = false)
    {   
        $this->data = $data;
        $curl = curl_init();       
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $this->data,
            CURLOPT_HTTPHEADER => $this->header(),
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);        
        $err = curl_error($curl);
        curl_close($curl);        
        if($httpStatus) {
            return $httpcode;
        }        
        return $response;
    }

    /** Curl API to send SMS **/
    function callAPI($method, $url, $data){
       $curl = curl_init();
       switch ($method){
          case "POST":
             curl_setopt($curl, CURLOPT_POST, 1);
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
             break;
          case "PUT":
             curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
             if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                              
             break;
          default:
             if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
       }
       // OPTIONS:
       curl_setopt($curl, CURLOPT_URL, $url);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       // EXECUTE:
       $result = curl_exec($curl);
       //print_r($result);exit;
       //if(!$result){die("Connection Failure");}
       curl_close($curl);
       return $result;
    }
}