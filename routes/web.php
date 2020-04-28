<?php

/* https://stackoverflow.com/questions/45809006/multiple-prefix-with-the-same-route-group/45809095 */


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

 
    


Route::group(['namespace' => 'Frontend'], function()
{
    Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm')->name('frontend.show-reset-form');
    Route::post('reset-password/reset', 'ResetPasswordController@reset')->name('frontend.reset-password'); 

    Route::middleware(['ifauth:'.GUARD_USER])->group(function() {        
        
        Route::get('/test', function () {
            return "sasas";
        });
        Route::post('signin','AuthController@signin')->name('frontend.signin');
        Route::post('signup','AuthController@register')->name('frontend.signup');
        Route::post('verify-otp','AuthController@verifyOTP')->name('frontend.verify-otp');
        Route::post('send-otp','AuthController@sendOTP')->name('frontend.send-otp');
        Route::post('password-reset', 'ForgetPasswordController@sendEmail')->name('frontend.send-reset-link');
        
        Route::get('login/google', 'AuthController@socialGoogleRedirectToProvider')->name('frontend.google-login');
        Route::get('login/google/callback', 'AuthController@socialGoogleHandleProviderCallback')->name('frontend.google-login-callback');

        Route::get('login/facebook', 'AuthController@socialFacebookRedirectToProvider')->name('frontend.facebook-login');
        Route::get('login/facebook/callback', 'AuthController@socialFacebookHandleProviderCallback')->name('frontend.facebook-login-callback');
    });

    Route::middleware(['unauth:'.GUARD_USER])->group(function() {
        
        Route::resource('user/address','UserAddressController');
        Route::post('user/profile-update','UserController@profileUpdate')->name('frontend.profile-update');
        
        Route::match(['GET','POST'], 'apply-corporate-voucher', 'CorporateVoucherController@applyVoucher')->name('frontend.corporate-voucher');

        Route::post('cart-update','CartController@userCart')->name('frontend.cart-update');
        Route::post('cart-quantity-update','CartController@updateQuantity')->name('frontend.cart-quantity-update');
        Route::get('cart-clear','CartController@clearCart')->name('frontend.clear-cart');
        Route::get('cart-view','CartController@getCart')->name('frontend.cart-view');
        

        Route::get('user/my-loyalty-points','HomeController@loyaltyPoints')->name('frontend.loyalty-points');
        Route::get('user/my-order','OrderController@index')->name('frontend.myorder');
        Route::get('user/my-order/{order_key}','OrderController@show')->name('frontend.show-myorder');
        Route::get('user/my-order-reorder/','OrderController@reOrders')->name('frontend.reorder');
        
        
        Route::match(['GET','PUT','POST'],'user/my-favourite-list','UserController@wishlist')->name('frontend.wishlist');
        Route::get('user/my-wallet','WalletController@wallet')->name('frontend.wallet');
        Route::post('wallet/add-money','WalletController@addMoney')->name('frontend.wallet-add');
        Route::post('wallet/redeem-points','WalletController@redeemPoint')->name('frontend.redeempoint');
        Route::match(['PUT','POST'],'branch-review','UserController@ratings')->name('frontend.post-rating');

        Route::post('apply-corporate-voucher', 'CorporateVoucherController@applyVoucher')->name('frontend.corporate-voucher');
        Route::get('checkout/{branch_slug}','OrderController@checkout')->name('frontend.checkout');
        Route::post('calculate-data','OrderController@calculateData')->name('frontend.calculate-data');
        Route::post('place-order','OrderController@placeOrder')->name('frontend.place-order');
    });

    Route::get('download-corporate-vouchers','CorporateVoucherController@downloadCorporateVoucher')->name('frontend.download-vouchers');
    
    Route::post('corporate-login','AuthController@corporateLogin')->name('frontend.corporate-login');
    Route::get('user/signout','AuthController@signout')->name('frontend.signout');
    Route::get('/','HomeController@index')->name('frontend.index');
    Route::get('offers','OfferController@offers')->name('frontend.offers');
    Route::post('get-branch-vouchers','OfferController@getBranchVouchers')->name('get-branch-vouchers');
    Route::post('language','HomeController@changeLanguage')->name('frontend.language');

    Route::resource('branch','BranchController',[
        'names' => [
            'index' => 'frontend.branch.index',
            'show' => 'frontend.branch.show',
        ]
    ]); 
    Route::get('get-near-branches','BranchController@nearBranchByVendor');   
    Route::resource('item','ItemController',[
        'names' => [
            'index' => 'frontend.item.index',
            'show' => 'frontend.item.show',
        ]
    ]);
    Route::get('detail','HomeController@detail')->name('frontend.detail');
    Route::post('newsletters','HomeController@newsletter')->name('frontend.newsletter');
    Route::get('order-confirmation','OrderController@confirmation')->name('frontend.confirmation');
    Route::get('order-failed','OrderController@orderFailed')->name('frontend.failed');
    Route::get('cms/{page}','HomeController@cms')->name('frontend.cms');
    Route::get('faq','HomeController@faq')->name('frontend.faq');
    Route::resource('contact','EnquiryController');
    Route::get('help','HomeController@help')->name('frontend.help');
    Route::post('driver-registration','HomeController@driverRegister')->name('frontend.driver-registration');
    
    Route::get('send-sms','HomeController@sendSms')->name('frontend.send-sms');
    Route::get('corporate-voucher','HomeController@corporateVoucherIndex');
});



/**
 * Admin Routes
 */

define('APP_GUARDS', [
    'admin' => GUARD_ADMIN, 
    'app/vendor' => GUARD_VENDOR,
    'app/outlet' => GUARD_OUTLET,
]);

$apps = APP_GUARDS;
$appRoute = 'admin';
$appGuard = GUARD_ADMIN;
foreach ($apps as $route => $guard) {
    /* var_dump(preg_match("$route/", request()->path()), request()->path(), '<br />'); */
    if (request()->is("$route/*")) {
        $appRoute = $route;
        $appGuard = $guard;
        break;
    }
}
define('APP_GUARD', $appGuard);

Route::group(['prefix' => $appRoute, 'namespace' => 'Admin'], function() use ($appGuard)
{    
    
    Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm')->name('reset-password');
    Route::post('reset-password/reset', 'ResetPasswordController@reset')->name('action-reset-password');

    Route::middleware(["ifauth:$appGuard"])->group(function () {
        Route::match(['GET','POST'],'login', 'AuthController@index')->name("admin-login");
        Route::match(['GET','POST'],'register', 'AuthController@showRegister')->name('admin-register');		
        /** Route::post('forgot-password', 'ForgetPasswordController@sendResetLinkEmail')->name('admin-forgot');  For automatic resend password link */
        Route::post('forgot-password', 'ForgetPasswordController@sendEmail')->name('admin-send-reset-link');
    });
    
        Route::middleware(["unauth:$appGuard",'adminStatus','adminAccess'])->group(function() {
                                
        Route::get('logout', 'AuthController@logout')->name('admin-logout');

        Route::resource('admin-user','AdminUserController');
        Route::post('admin-user/status','AdminUserController@status')->name('admin-user.status');
                
        Route::get('dashboard', 'DashboardController@index')->name('admin-dashboard');

        Route::post('settings', 'SettingsController@changeLangugage')->name('change-language');        

        Route::get('settings/app-settings', 'SettingsController@appSettings')->name('admin-app-settings');
        Route::post('settings/app-settings/save', 'SettingsController@saveAppSettings')->name('admin-app-settings-save');

        Route::get('settings/mail-settings', 'SettingsController@mailSettings')->name('admin-mail-settings');
        Route::get('settings/mail-testing', 'SettingsController@testMail')->name('admin-test-mail');
        Route::get('settings/social-media-settings', 'SettingsController@socialmediaSettings')->name('admin-social-media-settings');        
        Route::get('settings/currency-settings', 'SettingsController@currencySettings')->name('admin-currency-settings');
        Route::get('settings/corporate-settings', 'SettingsController@corporateSettings')->name('admin-corporate-settings');        
        Route::get('settings/sms-settings', 'SettingsController@smsSettings')->name('admin-sms-settings');
        Route::get('settings/sms-testing', 'SettingsController@testSms')->name('admin-test-sms');
        Route::get('settings/loyalty-point-settings', 'SettingsController@loyaltypointSettings')->name('admin-loyalty-point-settings');
        Route::get('settings/delivery-boy-settings', 'SettingsController@deliveryBoySettings')->name('admin-delivery-boy-settings');
        Route::post('settings/settings/save', 'SettingsController@saveSettings')->name('admin-settings-save');

        
        Route::resource('cuisine','CuisineController');		
        Route::post('cuisine/status','CuisineController@status')->name('cuisine.status');

        Route::resource('country','CountryController');		
        Route::post('country/status','CountryController@status')->name('country.status');

        Route::resource('city','CityController');
        Route::post('city/status','CityController@status')->name('city.status');
        Route::post('getcity-bycountry','CityController@getCity')->name('city-by-country');		
        Route::post('getarea-bycity','AreaController@getArea')->name('area-by-city');		

        Route::resource('area','AreaController');		
        Route::post('area/status','AreaController@status')->name('area.status');

        Route::resource('addresstype','AddressTypeController');		
        Route::post('addresstype/status','AddressTypeController@status')->name('addresstype.status');

        Route::resource('banner','BannerController');		
        Route::post('banner/status','BannerController@status')->name('banner.status');

        Route::resource('category','CategoryController');		
        Route::post('category/status','CategoryController@status')->name('category.status');
        
        Route::resource('delivery-area','DeliveryAreaController');		
        Route::post('delivery-area/status','DeliveryAreaController@status')->name('delivery-area.status');
        Route::post('delivery-area-by-area','DeliveryAreaController@getDeliveryArea')->name('delivery-area-by-area');		

        Route::resource('deliverycharge','DeliveryChargeController');	
        Route::post('deliverycharge/status','DeliveryChargeController@status')->name('deliverycharge.status');

        Route::resource('loyaltylevel','LoyaltyLevelController');	
        Route::post('loyaltylevel/status','LoyaltyLevelController@status')->name('loyaltylevel.status');
        
        Route::resource('vendor','VendorController');		
        Route::post('vendor/status','VendorController@status')->name('vendor.status');
        Route::post('vendor/popular','VendorController@popularstatus')->name('vendor.popularstatus');
        Route::post('vendor/approvedstatus','VendorController@approvedStatus')->name('vendor.approvedstatus');
        Route::post('get-category-by-vendor','VendorController@getBranchCategory')->name('get-category-by-vendor');
        Route::post('get-cuisine-by-vendor','VendorController@getBranchCuisine')->name('get-cuisine-by-vendor');
        
        Route::match(['get','post'],'branch/timeslot','BranchController@timeslot')->name('branch.timeslot');
        Route::match(['get','post'],'branch/timeslot-new','BranchController@timeslotnew')->name('branch.timeslot-new');
        Route::resource('branch','BranchController');
        Route::post('branch/status','BranchController@status')->name('branch.status');
        Route::post('branch/approvedstatus','BranchController@approvedStatus')->name('branch.approvedstatus');
        Route::post('get-branch-by-vendor','BranchController@getBranch')->name('get-branch-by-vendor');
        
        Route::resource('ingredient','IngredientController');		
        Route::post('ingredient/status','IngredientController@status')->name('ingredient.status');
        Route::post('get-vendor-ingredients','IngredientController@getIngredientsDepandsOnVendor')->name('get-vendor-ingredient');        
        
        Route::resource('ingredient-group','IngredientGroupController');		
        Route::post('ingredient-group/status','IngredientGroupController@status')->name('ingredient-group.status');
        
        Route::resource('offer','OfferController');
        Route::post('offer/status','OfferController@status')->name('offer.status');

        Route::resource('corporate-offer','CorporateOfferController');
        Route::post('corporate-offer/status','CorporateOfferController@status')->name('corporate-offer.status');

        Route::resource('vendorpayment','VendorPaymentController');
        
        Route::resource('item','ItemController');
        Route::post('item/status','ItemController@status')->name('item.status');
        Route::post('item/quikbuy','ItemController@quikbuystatus')->name('item.quikbuystatus');
        Route::post('item/approvedstatus','ItemController@approvedStatus')->name('item.approvedstatus');
        Route::post('get-item-by-branch','ItemController@getItembyBranch')->name('get-item-by-branch');        
        Route::post('get-item-by-branch-offer','ItemController@getItembyBranchOffer')->name('get-item-by-branch-offer');
        
        Route::resource('deliveryboy','DeliveryboyController');		
        Route::post('deliveryboy/status','DeliveryboyController@status')->name('deliveryboy.status');
        Route::get('deliveryboy-tracking','DeliveryboyController@trackDeliveryboy')->name('deliveryboy.tracking');
        Route::post('deliveryboy/approvedstatus','DeliveryboyController@approvedStatus')->name('deliveryboy.approvedstatus');

        Route::resource('loyaltypoint','LoyaltyPointController');	
        Route::post('loyaltypoint/status','LoyaltyPointController@status')->name('loyaltypoint.status');

        Route::resource('voucher','VoucherController');		
        Route::post('voucher/status','VoucherController@status')->name('voucher.status');

        Route::resource('enquiry','EnquiryController');	
        Route::post('enquiry/status','EnquiryController@status')->name('enquiry.status');

        Route::resource('newsletter','NewsletterController');	
        Route::post('newsletter/status','NewsletterController@status')->name('newsletter.status');
        
        
        Route::resource('newsletter-subscriber','NewsletterSubscriberController');	
        Route::post('newsletter-subscriber/status','NewsletterSubscriberController@status')->name('newsletter-subscriber.status');
        Route::match(['GET','POST'],'newsletter-send-mail','NewsletterSubscriberController@sendMail')->name('newsletter-sendmail');
        Route::get('newsletter-export','NewsletterSubscriberController@newsletterExport')->name('newsletter-export');

        Route::resource('cms','CmsController');	
        Route::post('cms/status','CmsController@status')->name('cms.status');

        Route::resource('faq','FaqController');	
        Route::post('faq/status','FaqController@status')->name('faq.status');

        Route::resource('user','UserController');
        Route::post('user/status','UserController@status')->name('user.status');

        Route::resource('useraddress','UserAddressController');
        Route::post('useraddress/status','UserAddressController@status')->name('useraddress.status');

        Route::resource('userwishlist','UserWishlistController');
        Route::post('userwishlist/status','UserWishlistController@status')->name('userwishlist.status');

        Route::resource('order','OrderController');        
        Route::post('order/status','OrderController@status')->name('order.status');
        Route::get('order-track/{order_key}','OrderController@trackOrder')->name('order.track');
        Route::post('order/approvedstatus','OrderController@approvedStatus')->name('order.approvedstatus');
        Route::get('order-export','OrderController@orderExport')->name('order-export');
        Route::post('order-get-available-deliveryboys','OrderController@getAvailableDeliveryboys')->name('order.get-available-deliveryboy');
        Route::post('order-assign-deliveryboy','OrderController@assignDeliveryboy')->name('order.assign-deliveryboy');

        Route::resource('corporate-order','CorporateOrderController');        
        Route::post('corporate-order/status','CorporateOrderController@status')->name('corporate-order.status');
        Route::post('corporate-order/approvedstatus','CorporateOrderController@corporateApprovedStatus')->name('corporate-order.approvedstatus');

        Route::resource('report','ReportController');
        Route::post('report/status','ReportController@status')->name('report.status');
        Route::post('report/approvedstatus','ReportController@approvedStatus')->name('report.approvedstatus');
        Route::get('report-export','ReportController@reportExport')->name('report-export');


        Route::resource('review','ReviewController');
        Route::post('review/status','ReviewController@status')->name('review.status');
        Route::post('review/approvedstatus','ReviewController@approvedStatus')->name('review.approvedstatus');
        
        Route::resource('role','RoleController');	
        Route::post('role/status','RoleController@status')->name('role.status');
        Route::get('403','RoleController@getRules');
        
        Route::resource('activity-log','ActivityLogController');    

       
    });
     Route::post('webpush-notification/register','DashboardController@webPushNotificationRegister');
    
    Route::fallback(function () {
        return response()->view('admin.errors.'.HTTP_NOT_FOUND,[] ,HTTP_NOT_FOUND);
    });    
});
