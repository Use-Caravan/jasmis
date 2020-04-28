<?php

namespace App\Helpers;

use common\models\Configuration;

/**
 * This OneSignal class is used to send push notifications to mobile applications
 * Class OneSignal
 * @package common\helpers
 *
 * @author A Vijay <vijay.a@technoduce.com>
 */
class OneSignal
{
    /**
     * One signal API URL
     */
    const URL = 'https://onesignal.com/api/v1/notifications';
    /**
     * @var static
     */
    private static $instance;
    /**
     * @var string
     */
    private $appId;
    /**
     * @var string
     */
    private $restApiKey;

    /**
     * OneSignal constructor.
     */
    public function __construct()
    {
        $this->setAppId(config('webconfig.one_signal_app_id'));
        $this->setRestApiKey(config('webconfig.one_signal_auth_id'));
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
    public function setRestApiKey($restApiKey)
    {
        $this->restApiKey = $restApiKey;
    }

    public function setAppType($appType)
    {
        switch($appType) {
            case ONE_SIGNAL_USER_APP:
                $this->appId = config('webconfig.one_signal_app_id');
                $this->restApiKey = config('webconfig.one_signal_auth_id');
                
                break;
            case ONE_SIGNAL_VENDOR_APP:
                $this->appId = config('webconfig.vendor_one_signal_app_id');
                $this->restApiKey = config('webconfig.vendor_one_signal_auth_id');
                break;
            case ONE_SIGNAL_DRIVER_APP:
                $this->appId = config('webconfig.deliveryboy_one_signal_app_id');
                $this->restApiKey = config('webconfig.deliveryboy_one_signal_auth_id');
                break;
            case ONE_SIGNAL_VENDOR_WEB_APP:    
                $this->appId = config('webconfig.vendor_one_signal_web_app_id');
                break;
        }        
        return $this;
    }

    /**
     * @param string $appId
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param array $title
     * @param array $message
     * @param array $clientIds
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidParamException
     */
    public function push(array $title, array $message, array $clientIds, array $data)
    {
        
        /* $this->log(str_repeat(' ', 60));
        $this->log(str_repeat('*', 60)); */

        $fields = [
            'app_id' => $this->appId,
            'contents' => $message,
            'headings' => $title,

            'android_channel_id' => '3f6085a1-ec49-4a16-8a87-b36ea19e769a',
            'android_sound' => 'customnotification.wav',
         
        ];
        if(count(array_filter($data))>0) {
            $fields['data'] = $data;
        }

        if ($clientIds === []) {
            $fields['included_segments'] = 'All Users';
        } else {        
            $fields['include_player_ids'] = $clientIds;                       
        }  
        $fields = json_encode($fields);

        $headers = [
            'Content-Type: application/json; charset=utf-8',
            sprintf('Authorization: Basic %s', $this->restApiKey)
        ];        

       // $this->log(print_r($fields, 1));       

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        # NotificationLog::log(Json::decode($fields), $response);
    }

    /**
     * @param $msg
     * @throws \yii\base\InvalidParamException
     */
    private function log($msg)
    {
        Com::log($msg, 'one-signal');
    }

}