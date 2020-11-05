<?php 

    const PER_PAGE 	= 10;    
    
    const USER_TYPE_CUSTOMER = 1;
    const USER_TYPE_CORPORATES = 2;

    const LAST_AUTH_USER = 'auth_user';

    const GUARD_USER = 'user-web';
    const GUARD_ADMIN = 'admin-web';    
    const GUARD_VENDOR = 'vendor-web';
    const GUARD_OUTLET = 'outlet-web';

    const PROVIDER_ADMIN_GUARD = 'admin';
    const PROVIDER_VENDOR_GUARD = 'vendor';
    const PROVIDER_BRANCH_GUARD = 'branch';
    const PROVIDER_USER_GUARD = 'users';

    const GUARD_USER_API = 'user-api';
    const GUARD_VENDOR_API = 'vendor-api';
    const GUARD_OUTLET_API = 'outlet-api';


    const GUARD_USER_API_PROVIDER = 'users';
    const GUARD_VENDOR_API_PROVIDER = 'vendor';
    const GUARD_OUTLET_API_PROVIDER = 'branch';



    const ROLE_USER_ADMIN = 1;
    const ROLE_USER_VENDOR = 2;
    const ROLE_USER_OUTLET = 3;

    const WEB_DATA = 'web_data';


    const ORDER_COUNT_TYPE_ALL = 1;
    const ORDER_COUNT_TYPE_PENDING = 2;
    const ORDER_COUNT_TYPE_DELIVERED = 3;
    const ORDER_COUNT_TYPE_REJECTED = 4;

    const ORDER_COUNT_DAY_TYPE_ALL = 1;
    const ORDER_COUNT_DAY_TYPE_TODAY = 2; 
    const ORDER_COUNT_DAY_TYPE_MONTH = 3; 
    const ORDER_COUNT_DAY_TYPE_YEAR = 4; 
    const ORDER_COUNT_DAY_TYPE_QUATER_YEAR = 5; 
    const ORDER_COUNT_DAY_TYPE_HALF_YEAR = 6; 
    const ORDER_COUNT_DAY_TYPE_WEEK = 7; 

    /**
     * All Crud Active and Inactive 
     */
	const ITEM_ACTIVE 	= 1;
    const ITEM_INACTIVE = 0;
    const CMS_SEC = [1,2];

    /**
     * Email And OTP
     * 
     */
    const YES = 1;
    const NO = 2;

    /**
     * All Ajax call response status
     */
    const AJAX_SUCCESS = 1;
    const AJAX_FAIL = 0;
    const PHONE_NUMBER_EXISTS = 2;
    const AJAX_VALIDATION_ERROR_CODE = 422;
    const EMAIL_EXISTS = 3;

    /**
     * Data Table Status and Action for Helper only
     */
    const TYPE_STATUS_COLUMN = 1;
    const TYPE_POPULARSTATUS_COLUMN = 0;
    const TYPE_QUICKBUYSTATUS_COLUMN = 4;
    const TYPE_ACTION_COLUMN = 2;
    const APPROVED_STATUS_COLUMN = 3;
    const TYPE_NEWITEMSTATUS_COLUMN = 5;

    /**
     * Storage paths for all files
     */

    
    const FRONT_END_BASE_PATH = 'resources/assets/frontend/';
    const ADMIN_END_BASE_PATH = 'resources/assets/admin/';
    const FILE_BASE_PATH = 'storage/app/';

    const PLACEHOLDER_IMAGE = 'resources/assets/general/noimage.png';

    const USER_PROFILE_IMAGE = 'gallery/user';
    const APP_LOGO_PATH = 'gallery/config';    
    const APP_HOME__BANNER_PATH = 'gallery/config';    
    const APP_BANNER_PATH = 'gallery/banners';

    const OFFER_BANNER_PATH = 'gallery/offer-banner';

    const CORPORATE_OFFER_BANNER_PATH = 'gallery/corporate-offer-banner';

    const VENDOR_LOGO_PATH = 'gallery/vendors';
    const BRANCH_LOGO_PATH = 'gallery/vendors';

    const CORPORATE_COMPANY_LOGO = 'gallery/corporate-company-logo';
    const CORPORATE_VOUCHER_FILES = 'vouchers';

    const APP_ITEM_PATH = 'gallery/items';
    const ORDER_ITEM_PATH = 'gallery/order-items';

    const APP_LOYALTY_LEVEL_PATH = 'gallery/loyalty-level';
    const APP_POPUP_IMAGE_PATH   = 'gallery/popup';

    /**
     * Gender
     */
    const MALE = 1;
    const FEMALE = 2;


     /**
     * CMS-Sections
     */
    const SEC_1 = 1;
    const SEC_2 = 2;
    const APP_CMS_PATH = 'gallery/cms';
    


    /**
     * Category type
     */
    const MAIN_CATEGORY = 1;
    const SUB_CATEGORY = 2;
    const APP_CATEGORY_PATH = 'gallery/category';

    /** Payment Options */
    const PAYMENT_OPTION_ONLINE = 1;//Credimax with credit card
    const PAYMENT_OPTION_COD = 2;
    const PAYMENT_OPTION_WALLET = 3;
    const PAYMENT_OPTION_ALL = 4;
    const WALLET_ZERO = 0;

    const CORPORATE_BOOKING_PAYMENT_ONLINE = 5;
    const CORPORATE_BOOKING_PAYMENT_CREDIT = 6;
    const CORPORATE_BOOKING_PAYMENT_LPO = 7;
    const PAYMENT_OPTION_WALLET_AND_ONLINE = 8;

    const PAYMENT_OPTION_CREDIT = 9;
    const PAYMENT_OPTION_NET_BANKING = 10;

    /**
     * Vendor Commission type 
     */
    const VENDOR_COMMISSION_TYPE_PERCENTAGE = 1;
    const VENDOR_COMMISSION_TYPE_AMOUNT = 2;

    /**
     * Restaurant Types
     */
    const RESTAURANT_TYPE_VEG = 1;
    const RESTAURANT_TYPE_NON_VEG = 2;
    const RESTAURANT_TYPE_BOTH = 3;
   
    /**
     * Order Types
     */    
    const ORDER_TYPE_DELIVERY = 1;
    const ORDER_TYPE_PICKUP_DINEIN = 2;
    const ORDER_TYPE_BOTH = 3;
    
    /**
     * Delivery Types
     */
    const DELIVERY_TYPE_ASAP = 1;
    const DELIVERY_TYPE_PRE_ORDER = 2;



    /**
     * Branch Availability Status
     */
    const AVAILABILITY_STATUS_OPEN = 1; 
    const AVAILABILITY_STATUS_CLOSED = 2;
    const AVAILABILITY_STATUS_BUSY = 3;
    const AVAILABILITY_STATUS_OUT_OF_SERVICE = 4;  

    /**
     * Branch Approved Status
     */
    const BRANCH_APPROVED_STATUS_PENDING = 0; 
    const BRANCH_APPROVED_STATUS_APPROVED = 1;
    const BRANCH_APPROVED_STATUS_REJECTED = 2; 

    /**
     * Delivery Area zone types
     */
    const DELIVERY_AREA_ZONE_CIRCLE = 1;
    const DELIVERY_AREA_ZONE_POLYGON = 2;


    /**
     * Delivery Boy Approved Status
     */
    const DELIVERY_BOY_APPROVED_STATUS_PENDING = 0; 
    const DELIVERY_BOY_APPROVED_STATUS_APPROVED = 1;
    const DELIVERY_BOY_APPROVED_STATUS_REJECTED = 2; 


    /** 
     * Delivery boy status
     */
    const DRIVER_ACTIVE =  1;
    const DRIVER_ONLINE = 2;
    const DRIVER_OFFLINE = 3;
    const DRIVER_INACTIVE = 4;
    const DRIVER_DEACTIVE = 5;
    const DRIVER_BUSY = 6;
    const DRIVER_DELETED = 7;
    const DRIVER_STOP_DUTY = 8;


     /**
     * Ingredient Types
     */
    const INGREDIENT_TYPE_MODIFIER = 1;
    const INGREDIENT_TYPE_SUBCOURSE = 2;

    /**
     * Item Approved Status
     */
    const ITEM_APPROVED = 1;
    const ITEM_UNAPPROVED = 2;

    /**
     * Voucher Discount types
     */
    const VOUCHER_DISCOUNT_TYPE_PERCENTAGE = 1;
    const VOUCHER_DISCOUNT_TYPE_AMOUNT = 2;

    /**
     * Voucher Discount types
     */
    const CORPORATE_OFFER_TYPE_QUANTITY = 1;
    const CORPORATE_OFFER_TYPE_AMOUNT = 2;    


    /**
     * Voucher Apply Promo 
     */
    const VOUCHER_APPLY_PROMO_SHOPS = 1;
    const VOUCHER_APPLY_PROMO_USERS = 2;
    const VOUCHER_APPLY_PROMO_BOTH = 3;

    /**
     * Voucher Promo For All
     */
    const PROMO_FOR_ALL_SHOPS = 1;
    const PROMO_FOR_ALL_USERS = 2;
    const PROMO_FOR_BOTH = 3;

    /**
     * Promo For All For Shops And User
     */
    const PROMO_SHOPS_ALL = 1;
    const PROMO_SHOPS_PARTICULAR = 2;

    const PROMO_USER_ALL = 1;    
    const PROMO_USER_PARTICULAR = 2;

    /**
     * Voucher App Types
     */
    const APP_TYPE_WEB = 1;
    const APP_TYPE_ANDROID = 2;
    const APP_TYPE_IOS = 3;
    const APP_TYPE_WINDOWS = 4;

    /**
     * Admin User
     */
    const ADMIN = 1;
    const SUB_ADMIN = 2;

    const SES_ROLE_JSON = 'SES_ROLE_JSON';

    const SESSION_LANGUAGE = 'language';

    
    /**
     * Configuration types
     */
    const CONFIG_APP = 1;
    const CONFIG_MAIL = 2;
    const CONFIG_SMS = 3;
    const CONFIG_SOCIAL_MEDIA = 4;
    const CONFIG_CURRENCY =  5;
    const CONFIG_LOYALTY_POINT = 6;
    const DELIVERY_BOY = 7;
    const CONFIG_CORPORATE = 8;


    /**
     * Currency Positions
     */
    const CURRENCY_RIGHT = 1;
    const CURRENCY_LEFT = 2;


    /** API Response code and message */
    const HTTP_NOT_FOUND = 404;
    const HTTP_SUCCESS = 200;
    const HTTP_UNPROCESSABLE = 422;
    const EXPECTATION_FAILED = 417;
    const OTP_VERFICATION_REQUIRED = 419;
    const UNAUTHORISED = 401;    
    const FORBIDDEN_UNAUTHORISED = 403;        
    const HTTP_ERROR = 500;


    /** Order status color */
    const ORDER_PENDING_COLOR = '#F0A007';
    const ORDER_ACCEPTED_COLOR = '#40c403';
    const ORDER_REJECTED_COLOR = '#F3082E';
    const ORDER_PREPARING_COLOR = '#0394E7';
    const ORDER_READY_FOR_PICKUP_COLOR = '#40c403';
    const ORDER_DRIVER_PICKUP_UP_COLOR = '#40c403';
    const ORDER_DELIVERED_COLOR = '#38A53D';
    const ORDER_COMPLETED_COLOR = '#38A53D';
    const ORDER_DEFAULT_COLOR = '#000000';

    /** Order Payment status */
    const ORDER_PAYMENT_PENDING = 0;
    const ORDER_PAYMENT_SUCCESS = 1;
    const ORDER_PAYMENT_FAILIUR = 2;

    /**
     * Branch Approved Status
     */
    const ORDER_APPROVED_STATUS_PENDING = 0; 
    const ORDER_APPROVED_STATUS_APPROVED = 1;
    const ORDER_APPROVED_STATUS_REJECTED = 2;
    const ORDER_APPROVED_STATUS_PREPARING = 3;
    const ORDER_APPROVED_STATUS_DRIVER_ACCEPTED = 4;
    const ORDER_APPROVED_STATUS_READY_FOR_PICKUP = 5;
    const ORDER_APPROVED_STATUS_DRIVER_PICKED_UP = 6; /* Not used tempourerly */
    const ORDER_APPROVED_STATUS_DELIVERED = 7;
    const ORDER_APPROVED_STATUS_COMPLETED = 8;  /* Not used tempourerly */
    const ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER = 9;
    const ORDER_ONTHEWAY = 10; 
    const ORDER_DRIVER_DELIVERED = 11; /* Not used tempourerly */
    const ORDER_DRIVER_REQUESTED = 12;
    const ORDER_DRIVER_REJECTED = 13;


    /**
     * Node Order Status 
     */
    const NODE_ORDER_PENDING = 1;
    const NODE_ORDER_ACCEPTED = 2;
    const NODE_ORDER_PREPARED = 3;
    const NODE_ORDER_ONTHEWAY = 4;
    const NODE_ORDER_DELIVERED = 5;
    const NODE_ORDER_REJECTED = 6;
    const NODE_ORDER_DRIVER_ASSIGNED = 7;
    const NODE_ORDER_DRIVER_ACCEPTED = 8;
    const NODE_ORDER_DRIVER_REJECTED = 9;
    const NODE_ORDER_DRIVER_DELIVERED = 10;
    const NODE_ORDER_DRIVER_REQUESTED = 11;
    const NODE_ORDER_READY_TO_PICKUP = 12;


    /** 
     * Order Assign type
     */
    const ORDER_ASSIGN_TYPE_AUTOMATIC = 1;
    const ORDER_ASSIGN_TYPE_MANUAL = 2;


    /**
     * Review Approved Status
     */

    const REVIEW_APPROVED_STATUS_PENDING = 0;
    const REVIEW_APPROVED_STATUS_APPROVED = 1;
    const REVIEW_APPROVED_STATUS_REJECTED = 2;
        

    /**
     * Payment Status
     */

    const ORDER_PAYMENT_STATUS_PENDING = 0;
    const ORDER_PAYMENT_STATUS_SUCCESS = 1;     
    const ORDER_PAYMENT_STATUS_FAILURE = 2;     

    /** Device types */
    const DEVICE_TYPE_WEB = 1;
    const DEVICE_TYPE_ANDROID = 2;
    const DEVICE_TYPE_IOS = 3;
    const DEVICE_TYPE_WINDOWS = 4;

    /** Login Types */
    const LOGIN_TYPE_APP   = 1;
    const LOGIN_TYPE_APPLE = 2;
    const LOGIN_TYPE_FB    = 3;
    const LOGIN_TYPE_GP    = 4;   
    
    /** OTP Verification status */
    const OTP_VERIFIED = 1;
    const OTP_UNVERIFIED = 2;

    /** OTP Purpose */
    const OTP_PURPOSE_CREATE_ACCOUNT = 1;
    const OTP_PURPOSE_FORGET_PWD = 2;
    const OTP_PURPOSE_CHANGE_PHONE_NO = 3;
    const OTP_PURPOSE_CHANGE_PLACE_ORDER = 4;
    
    /** Payment Transaction for */
    const ORDER_TRANSACTION = 1;
    const WALLET_TRANSACTION = 2;
    const POINTS_REDEEM_TRANSACTION = 3;

    /** Transaction type */
    const CREDIT = 1;
    const DEBIT = 2;



    /**
     * API Payment Details Constants
     */
    const PAYMENT_GRAND_TOTOAL_COLOR = '#fe1509';
    const PAYMENT_SUB_TOTOAL_COLOR = '#D3D3D3';
    const PAYMENT_VAR_TAX_COLOR = '#D3D3D3';
    const PAYMENT_SERVICE_TAX_COLOR = '#D3D3D3';
    const PAYMENT_DELIVERY_FEE_COLOR = '#D3D3D3';
    const PAYMENT_COUPON_FEE_COLOR = '#e6e6e6';
    const IS_BOLD = 0;
    const SUB_TOTAL_BOLD = 1;
    const IS_ITALIC = 0;
    const IS_LINE = 0;



    /** 
     * PHP Methods 
    */
    const METHOD_PUT = 'PUT';
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_DELETE = "DELETE";
    const METHOD_PATCH = "PATCH";

    
    const ONE_SIGNAL_USER_APP = 1;
    const ONE_SIGNAL_VENDOR_APP = 2;
    const ONE_SIGNAL_DRIVER_APP = 3;
    const ONE_SIGNAL_VENDOR_WEB_APP = 4;


    const TRANSACTION_STATUS_PENDING = 1;
    const TRANSACTION_STATUS_SUCCESS = 2;
    const TRANSACTION_STATUS_CANCELLED = 3;
    const TRANSACTION_STATUS_FAILED = 4;

    const TRANSACTION_FOR_ONLINE_BOOKING = 1;
    const TRANSACTION_FOR_WALLET_BOOKING = 4;
    const TRANSACTION_FOR_ADD_TO_WALLET = 2;
    const TRANSACTION_FOR_POINT_REDEEM = 3;

    const TRANSACTION_TYPE_CREDIT = 1;
    const TRANSACTION_TYPE_DEBIT = 2;

    const FIRE_BASE_USER_APP = 1;
    const FIRE_BASE_VENDOR_APP = 2;
    const FIRE_BASE_DRIVER_APP = 3;

?>