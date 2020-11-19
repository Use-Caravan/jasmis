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
    'one_signal_app_id' => '9e54be7d-f073-40d8-b653-1ee8c3ff558e',
    'one_signal_auth_id' => 'OTBkZGMxMTUtOTZhMC00OGQxLTk2YjEtYTZkNzJkMmYzOTIy',
    'one_signal_android_channel_id' => '6ac90291-30a5-4507-a033-58adf01c5e5c',
    'vendor_one_signal_app_id' => '88a7e692-18ea-4b24-969c-1406682623f7',//'3c4bb844-f83f-4d84-a16c-94822192199d',//'1:831643271249:android:8ee25feffbf18f71',
    'vendor_one_signal_auth_id' => 'ZTYzNGZiZGItMmIzZi00NzU3LThiZjYtNGUxMmQ4OWMwMWQ2',//'Zjc1MTU2MGYtNTA2My00Mjg0LTkwZGItMDZiMjRiOGI1Yzk3',
    'vendor_android_channel_id' => '6c00dcb5-b71f-45c2-b29f-6fcc6c06bed0',
    'deliveryboy_one_signal_app_id' => '708c1c35-4a9b-40f0-a756-d9f9e28ba3d8',//'ba1b7194-fea2-42c5-8e8c-234aa0dc3602',
    'deliveryboy_one_signal_auth_id' => 'NWNiNmQ3MDctMDJmMi00MDA4LTkxM2UtYjEyOWZlMmViZmIx',//'OWU0NGU5NzctYjAzYy00YTg5LWExOTgtYjIxMDViY2ZlOTFk',
    'deliveryboy_android_channel_id' => 'adf530b6-202c-40a0-a223-de0238d240a7',
    'vendor_one_signal_web_app_id' => 'bb42b8c9-7dd8-45e4-9302-de954ddc1c5a',
    //'vendor_one_signal_app_id' => '3f6085a1-ec49-4a16-8a87-b36ea19e769a',




    
    /** 
     * Delivery boy Configuration 
     * 
     */
     'first_cut_off_time_limit' => 0,
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
    
    
    //"credimaxpay_benefit_checkout_url" => "https://api.credimaxpay.com/api/benefit_checkout",
    //"credimaxpay_credit_card_checkout_url" => "https://api.credimaxpay.com/api/v2/checkout",

    //Credimax updated URLs https://docs.google.com/document/d/1uz68G4LHUxjL_P1cy06kefJgz3JoPBtBIgPCJ_V4ez4/edit
    "credimaxpay_benefit_checkout_url" => "https://api.paygcc.com/api/v2/benefit_checkout",
    "credimaxpay_credit_card_checkout_url" => "https://api.paygcc.com/api/v2/checkout",

    //"credimaxpay_company_code" => "iRSn0PAEcsKwHpKCICx8vjLEsBAk0SltAXWx4b83eXdZVZ9OXSb0OOB1SOO9NWh8",
    //Credimax company code updated
    "credimaxpay_company_code" => "G6f7uoPpL0QHdpf0jBBAlqD33uTFxnfw5zfxAHLtn5SE69r91mpmUJGYusKebIyz",

    //"credimaxpay_payment_details_url" => "https://api.credimaxpay.com/api/orderPaymentDetails",
    "credimaxpay_payment_details_url" => "https://api.paygcc.com/api/orderPaymentDetails",
    
    "credimax_payment_gateway_success_url" => "/api/v1/payment-gateway/credimax-success",
    "credimax_payment_gateway_failure_url" => "/api/v1/payment-gateway/credimax-failure",
    "credimax_payment_gateway_wallet_success_url" => "/api/v1/payment-gateway/credimax-wallet-success",
    "credimax_payment_gateway_wallet_failure_url" => "/api/v1/payment-gateway/credimax-wallet-failure",
    "credimax_payment_gateway_success_url_debit" => "/api/v1/payment-gateway/credimax-success-debit",
    "credimax_payment_gateway_failure_url_debit" => "/api/v1/payment-gateway/credimax-failure-debit",

    "fcm_url" => "https://fcm.googleapis.com/fcm/send",

    "customer_fire_base_key" => "AAAAgpIqCu8:APA91bE7s_eKz9DfVfSPanYCODbgzo78FJYTQAGCw9uMZfdv9JQdBlOkPAGOzbTESILHnJLyx8b0E9OmfeePWOVvEoEnaC86QD_hkfZQQjtvKtPp0mpHlT3ykOJnyBp2GMgx9eOWAHPs",
    "vendor_fire_base_key" => "AAAAwaHNQFE:APA91bGhnfqoQEu47cZ4cPJqgZn11dD9ZWFVpTp5poPd65aaWedkoB56y-eeAI6Sw8b_LoHx-69gnAp3uh5hPSfXDrzd_IITwFQO2Y9yEbexeqTmhdj56GMQkSj1GN-a38t0wjwuZYYr",
    "rider_fire_base_key" => "AAAA04e1qe8:APA91bH6m3x_KFNQ9ovSUov8g6jd3XM8gUuo_UKsYCkVLSsfD92yNJvuMKwktVW-21ze2l2v48NFHcULl9zTNfl2hvhKGHOjbV19J9tZwKX4LD7LvjjDbWDNK9FYpBGKeaaiSO57o3r0",
];

