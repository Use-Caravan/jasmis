<?php

return [

    /**
     * Application Configuration
     */

    'app_name'          => '',
    'app_logo'          => '',
    'app_favicon'       => '',
    'app_description'   => '',
    'app_meta_keywords' => '',
    'app_meta_description'  => '',
    'app_email'         => '',
    'app_contact_number'=> '',    
    'app_primary_color' => '',
    'app_address' => '',
    'phone_number_code' => '973',


    'home_banner' => '',
    'home_banner_text' => '',

    'app_latitude'      => '',
    'app_longitude'     => '',

    'app_inv_prefix' => 'CRN',
    'app_date_format' => 'd-m-Y h:i A',


    'play_store_link'   => '',
    'app_store_link'    => '',
    'map_key'           => '',
    


    /**
     * Mail COnfiguration
     */
    'smtp_host'         => '',
    'smtp_username'     => '',
    'smtp_password'     => '',
    'encryption'        => '',
    'port'              => '',    
    'is_smtp_enabled'   => '',
    
    
    /**
     * Social Media Links
     */
    'social_facebook'          => '',
    'social_instagram'         => '',
    'social_twitter'           => '',    
    'social_google'           => '',


    /**
     * Currency Management
     */
    'currency_code'    => '',
    'currency_symbol'  => '',
    'currency_position'=> '',
    'currency_decimal'=> '3',


    /**
     * SMS Configuration
     */
    'sms_gateway_username' => '',
    'sms_gateway_password' => '',
    'sms_sender_id'        => '',
    'is_sms_enabled'       => '',


    /**
     * Loyalty Points Configuration
     *  Ex : Amount ( 1 ) = ( 10 ) Points 
     *  Ex : Points ( 10 ) = ( 1 ) Amount
     */
    'loyalty_amount'            => '',
    'loyalty_point_for_amount'  => '',
    'loyalty_points'            => '',
    'loyalty_amount_for_points' => '',


    /** 
     * One Signal Configuration 
     */  
    'one_signal_app_id' => '8c088e27-9a9e-4c71-957a-d114b5c9bc9f',
    'one_signal_auth_id' => 'NTBiMDg1NzEtNjA0ZS00NTk0LTllYTYtMGRlNjRkZjE3OGEy',
    'vendor_one_signal_app_id' => '3c4bb844-f83f-4d84-a16c-94822192199d',
    'vendor_one_signal_auth_id' => 'Zjc1MTU2MGYtNTA2My00Mjg0LTkwZGItMDZiMjRiOGI1Yzk3',
    'deliveryboy_one_signal_app_id' => 'ba1b7194-fea2-42c5-8e8c-234aa0dc3602',
    'deliveryboy_one_signal_auth_id' => 'OWU0NGU5NzctYjAzYy00YTg5LWExOTgtYjIxMDViY2ZlOTFk',
    'vendor_one_signal_web_app_id' => 'bb42b8c9-7dd8-45e4-9302-de954ddc1c5a',
    //'vendor_one_signal_app_id' => '3f6085a1-ec49-4a16-8a87-b36ea19e769a',




    
    /** 
     * Delivery boy Configuration 
     * 
     */
     'order_accept_time_limit' => 0,
     'request_radius' => 0,
     'order_assign_type' => 1,
     'deliveryboy_url' => '',
     'company_id' => '',
     'auth_token' => '',     
     'tracking_url' => env('SOCKET_TRACKING_URL','https://nodeboxfood.duceapps.com'),


    'country_code' => '+973',



    "payment_gateway_mode" => 'sandbox',
    "payment_gateway_base_url" => [
         'sandbox' => 'https://eps-net-uat.sadadbh.com',
         'live' => 'https://eps-net.sadadbh.com',
        ],
    "payment_gateway_api_url" => "/api/v2/web-ven-sdd/epayment/create/",
    // "payment_gateway_api_key" => "3246ea73-c521-4d6b-878c-c00ee3ef80b0",
    // "payment_gateway_vendor_id" => 2620,
    // "payment_gateway_branch_id" => 3663,
    // "payment_gateway_terminal_id" => 4324,
    // "payment_gateway_notification_mode" => 300,

    // "payment_gateway_success_url" => "https://usecaravan.com/api/v1/payment-gateway/success",
    // "payment_gateway_failiur_url" => "https://usecaravan.com/api/v1/payment-gateway/failiur",



 
    "payment_gateway_api_key" => "8a868611-020f-4aa5-8cac-1e651b59b065",
    "payment_gateway_vendor_id" => 152,
    "payment_gateway_branch_id" => 193,
    "payment_gateway_terminal_id" => 262,
    "payment_gateway_notification_mode" => 300,
    "payment_gateway_success_url" => "https://caravan.brigita.co/api/v1/payment-gateway/success",
    "payment_gateway_failiur_url" => "https://caravan.brigita.co/api/v1/payment-gateway/failiur", 
    
    
    "credimaxpay_benefit_checkout_url" => "https://api.credimaxpay.com/api/benefit_checkout",
    "credimaxpay_company_code" => "iRSn0PAEcsKwHpKCICx8vjLEsBAk0SltAXWx4b83eXdZVZ9OXSb0OOB1SOO9NWh8",
    "credimaxpay_payment_details_url" => "https://api.credimaxpay.com/api/orderPaymentDetails",
    "credimax_payment_gateway_success_url" => "/api/v1/payment-gateway/credimax-success",//https://caravan.brigita.co
    "credimax_payment_gateway_failure_url" => "/api/v1/payment-gateway/credimax-failure",
];

