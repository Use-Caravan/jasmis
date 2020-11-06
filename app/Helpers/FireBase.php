<?php

namespace App\Helpers;

use common\models\Configuration;

/**
 * This FireBase class is used to send push notifications to mobile applications
 * Class FireBase
 * @package common\helpers
 *
 * 
 */
class FireBase
{
    /**
     * @var static
     */
    private static $instance;
    /**
     * @var string
     */
    private $fireBaseKey;

    /**
     * OneSignal constructor.
     */
    public function __construct()
    {
        //$this->setAppId(config('webconfig.one_signal_app_id'));
        //$this->setRestApiKey(config('webconfig.one_signal_auth_id'));
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param mixed $restApiKey
     */
    public function setFireBaseKey($fireBaseKey)
    {
        $this->fireBaseKey = $fireBaseKey;
    }

    public function setAppType($appType)
    {
        switch($appType) {
            case FIRE_BASE_USER_APP:
                $this->fireBaseKey = config('webconfig.customer_fire_base_key');
                break;
            case FIRE_BASE_VENDOR_APP:
                $this->fireBaseKey = config('webconfig.vendor_fire_base_key');
                break;
            case FIRE_BASE_DRIVER_APP:
                $this->fireBaseKey = config('webconfig.rider_fire_base_key');
                break;
        }        
        return $this;
    }

    /**
     * @param array $title
     * @param array $message
     * @param array $clientIds
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidParamException
     */    
    public function push( $header_text, $title, $message, $device_token, array $data, $is_new_order = "No" )
    {
        $fcmUrl = config('webconfig.fcm_url');
        $token = $device_token;

        $notification = [
            'headerText' => $header_text,
            'title' => $title,
            'content' => $message,
            'imageUrl' => "",
            "order" => $is_new_order,
            'sound' => true,
            'type' => 1
        ];
        
        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'data' => $notification
        ];

        $headers = [
            'Authorization: key='.$this->fireBaseKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}