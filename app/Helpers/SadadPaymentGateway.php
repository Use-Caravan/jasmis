<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Helpers\Curl;

/**
 * Class SadadPaymentGateway
 * @package App\Helpers
 *
 * @author Aravindan R <aravindan.r@technoduce.com>
 */
class SadadPaymentGateway
{
    /**
     * @var 
     */
    public static $instance;

    /**
     * 
     * @var string
     */
    public $customerName;

    /**
     * 
     * @var string
     */
    public $customerPhone;

    /**
     * 
     * @var string
     */
    public $customermail;

    /**
     * 
     * @var double
     */
    public $amount;


    /**
     * 
     * @var double
     */
    public $description = "Caravan Foood Prodcts";

    /**
     * @var boolean default mobile device
     */
    public $is_web = false;


    public static function instance($refresh = false)
    {   
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    

    /**
     * @param string $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
        return $this;
    }

    /**
     * @param string $customerPhone
     */
    public function setCustomerPhone($customerPhone)
    {
        $this->customerPhone = $customerPhone;
        return $this;
    }

    /**
     * @param string $customerMail
     */
    public function setCustomerMail($customerMail)
    {
        $this->customerMail = $customerMail;
        return $this;
    }

    /**
     * @param  double $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {   
        $this->description = $description;
        return $this;
    }

    /**
     * @param boolean $from
     */
    public function setRequestFrom($isWeb)
    {        
        $this->is_web = $isWeb;
        return $this;
    }

    /**
     * @return array response of payment gateway
     */
    public function makePayment()
    {        
        $data = [
            "api-key" => config('webconfig.payment_gateway_api_key'),
            "vendor-id" => config('webconfig.payment_gateway_vendor_id'),
            "branch-id" => config('webconfig.payment_gateway_branch_id'),
            "terminal-id" => config('webconfig.payment_gateway_terminal_id'),

            "msisdn" => config('webconfig.country_code').$this->customerPhone,
            "email" => $this->customerMail,
            "customer-name" => $this->customerName,
            "amount" => $this->amount,
            "description" => $this->description,
            "remarks" => "",

            "date" => date("Y-m-d\TH:i:s.000\Z", strtotime( date("Y-m-d H:i:s") )),
            "external-reference" => rand(1111111,999999),
            "notification-mode" => config('webconfig.payment_gateway_notification_mode'),
            "success-url" => config('webconfig.payment_gateway_success_url')."?is_web=$this->is_web",
            "error-url" => config('webconfig.payment_gateway_failiur_url')."?is_web=$this->is_web",
        ];
        $data = json_encode($data);    
        $url = config('webconfig.payment_gateway_base_url')[config('webconfig.payment_gateway_mode')].config('webconfig.payment_gateway_api_url');        
        $response = Curl::instance()->action("POST")->setUrl($url)->send($data);
        return json_decode($response,true);
    }
}