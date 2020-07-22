<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use App\Helpers\Curl;

/**
 * Class CredimaxPaymentGateway
 * @package App\Helpers
 *
 * 
 */
class CredimaxPaymentGateway
{
    /**
     * @var 
     */
    public static $instance;

    /**
     * 
     * @var int
     */
    public $customerId;

    /**
     * 
     * @var int
     */
    public $orderId;

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
     * @param int $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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
            "customer_id" => $this->customerId,
            "order_id" => $this->orderId,
            "grand_total" => $this->amount,
            "currency_code" => "BD",
            "payment_type" => 2,
            "success_redirect_url" => url('/').config('webconfig.credimax_payment_gateway_success_url')."?is_web=$this->is_web&order_id=$this->orderId",
            "failed_redirect_url" => url('/').config('webconfig.credimax_payment_gateway_failure_url')."?is_web=$this->is_web&order_id=$this->orderId",
        ];
        $data = json_encode($data);    
        $url = config('webconfig.credimaxpay_benefit_checkout_url');        
        $response = Curl::instance()->action("POST")->setUrl($url)->send($data);
        return json_decode($response,true);
    }

    /**
     * @return array response of payment gateway
     */
    public function getPaymentDetails()
    {        
        $data = [
            "order_id" => $this->orderId
        ];
        $data = json_encode($data);    
        $url = config('webconfig.credimaxpay_payment_details_url');        
        $response = Curl::instance()->action("POST")->setUrl($url)->send($data);
        return json_decode($response,true);
    }
}