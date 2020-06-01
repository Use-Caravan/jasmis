<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::get('/test', function()
{
    return 'Hello World';
});
Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'],function()
{        
    Route::post('password-reset', 'ForgetPasswordController@sendEmail');
    Route::post('create-account','AuthController@register');    
    Route::post('social-auth','AuthController@socialAuth');
    Route::post('send-otp','AuthController@sendOTP');
    Route::post('verify-otp','AuthController@verifyOTP');
    Route::post('login','AuthController@login')->name('login');
    
    Route::resource('area','AreaController');
    Route::resource('addresstype','AddressTypeController');
    Route::resource('cuisine','CuisineController');
    Route::resource('item','ItemController');
    Route::resource('branch','BranchController');
    Route::post('timeslot','BranchController@branchTimeslot');
    Route::resource('enquiry','EnquiryController');
    Route::get('contact-details','EnquiryController@contactDetails');
    Route::resource('cms','CmsController');
    Route::get('get-homepage','CmsController@gethomepage');
    Route::resource('voucher','VoucherController');
    Route::get('send-notification', 'EnquiryController@sendNotification');
    Route::get('voucher-branches','VoucherController@getVoucherBranches');
    Route::get('get-vouchers','VoucherController@getVouchers');
    Route::get('offer', 'OfferController@getOffers');
    Route::get('get-near-branches/{vendor_key?}/{branch_key?}','BranchController@branchByVendor');
    

    /** Node team status update */
    Route::get('save-order-develiveryboy/{order_key}','OrderController@saveOrderOnDeliveryBoy');
    Route::get('order/deliveryboy','OrderController@orderStatusUpdate');
    /** Node team status update */
       
    /**
     * Payment Gateway redirect url
     */
    Route::post('payment-gateway/success','PaymentGatewayController@success');
    Route::post('payment-gateway/failiur','PaymentGatewayController@failiur');

    Route::group(['middleware' => ['auth:'.GUARD_USER_API] ],function()
    {
        Route::get('logout','AuthController@logout');       

        Route::match(['GET','PUT','POST'],'user-details','UserController@userDetails');
        Route::post('wallet/add-money','WalletController@addMoney');
        Route::post('wallet/redeem-points','WalletController@redeemPoint');
        Route::put('user-change-password','UserController@changePassword');
        Route::match(['GET','PUT','POST'],'user-wishlist','UserController@wishlist');
        Route::match(['PUT','POST'],'branch-review','UserController@ratings');
        Route::post('profile-image','UserController@profileImage');

        Route::resource('useraddress','UserAddressController');    
        Route::get('my-order','OrderController@index');
        Route::get('my-order/{order_key}','OrderController@show');
        Route::get('my-order-reorder','OrderController@reOrder');
        Route::post('calculate-data','OrderController@calculateData');
        Route::post('place-order','OrderController@placeOrder');        

        Route::post('cart-update','CartController@userCart');
        Route::post('cart-quantity-update','CartController@updateQuantity');
        Route::get('cart-view','CartController@getCart');
        Route::get('cart-clear','CartController@clearCart');

        Route::get('loyalty-point','LoyaltyPointController@loyaltyPointDetails');

    });
    Route::get('order-confirmation-mail/{order_key}','OrderController@sendConfirmationMail');


    Route::group(['prefix' => 'vendor', 'namespace' => 'Vendor'],function()
    {
        Route::post('login','AuthController@login');  

        Route::group(['middleware' => ["auth:".GUARD_OUTLET_API.",".GUARD_VENDOR_API]],function()
        {
            Route::get('logout','AuthController@logout');
            Route::match(['GET','PUT'],'profile','VendorController@profile');
            Route::put('change-branch-status','VendorController@changeBranchStatus');
            Route::get('incoming-orders','OrderController@incomingOrders');
            Route::get('accepted-orders','OrderController@acceptedOrders');
            Route::get('view-order/{order_key}','OrderController@showOrder');
            Route::put('order-change-status/{order_key}','OrderController@changeStatus');            
            Route::get('orders-report','OrderController@report');
            Route::get('branch-status','VendorController@BranchStatus');
        });

    });


    Route::fallback(function () {
        $response = ['status' => 'fail', 'code' => HTTP_NOT_FOUND, 'message' => 'URL Not Found.', 'time'=> time()];
        return response()->json($response,HTTP_NOT_FOUND);
    });

});




    