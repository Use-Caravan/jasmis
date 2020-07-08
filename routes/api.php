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
    Route::post('password-reset', 'ForgetPasswordController@sendEmail')->middleware('localization');
    Route::post('create-account','AuthController@register')->middleware('localization');    
    Route::post('social-auth','AuthController@socialAuth')->middleware('localization');
    Route::post('send-otp','AuthController@sendOTP')->middleware('localization');
    Route::post('verify-otp','AuthController@verifyOTP')->middleware('localization');
    Route::post('login','AuthController@login')->name('login')->middleware('localization');
    
    Route::resource('area','AreaController')->middleware('localization');
    Route::resource('addresstype','AddressTypeController')->middleware('localization');
    Route::resource('cuisine','CuisineController')->middleware('localization')->middleware('localization');
    Route::resource('item','ItemController')->middleware('localization');
    Route::resource('branch','BranchController')->middleware('localization');
    Route::post('timeslot','BranchController@branchTimeslot')->middleware('localization');
    Route::resource('enquiry','EnquiryController')->middleware('localization');
    Route::get('contact-details','EnquiryController@contactDetails')->middleware('localization');
    Route::resource('cms','CmsController')->middleware('localization');
    Route::get('get-homepage','CmsController@gethomepage')->middleware('localization');
    Route::resource('voucher','VoucherController')->middleware('localization');
    Route::get('send-notification', 'EnquiryController@sendNotification')->middleware('localization');
    Route::get('voucher-branches','VoucherController@getVoucherBranches')->middleware('localization');
    Route::get('get-vouchers','VoucherController@getVouchers')->middleware('localization');
    Route::get('offer', 'OfferController@getOffers')->middleware('localization');
    Route::get('get-near-branches/{vendor_key?}/{branch_key?}','BranchController@branchByVendor')->middleware('localization');
    

    /** Node team status update */
    Route::get('save-order-develiveryboy/{order_key}','OrderController@saveOrderOnDeliveryBoy')->middleware('localization');
    Route::get('order/deliveryboy','OrderController@orderStatusUpdate')->middleware('localization');
    /** Node team status update */
       
    /**
     * Payment Gateway redirect url
     */
    Route::post('payment-gateway/success','PaymentGatewayController@success')->middleware('localization');
    Route::post('payment-gateway/failiur','PaymentGatewayController@failiur')->middleware('localization');

    Route::group(['middleware' => ['auth:'.GUARD_USER_API] ],function()
    {
        Route::get('logout','AuthController@logout')->middleware('localization');       

        Route::match(['GET','PUT','POST'],'user-details','UserController@userDetails')->middleware('localization');
        Route::post('wallet/add-money','WalletController@addMoney')->middleware('localization');
        Route::post('wallet/redeem-points','WalletController@redeemPoint')->middleware('localization');
        Route::put('user-change-password','UserController@changePassword')->middleware('localization');
        Route::match(['GET','PUT','POST'],'user-wishlist','UserController@wishlist')->middleware('localization');
        Route::match(['PUT','POST'],'branch-review','UserController@ratings')->middleware('localization');
        Route::post('profile-image','UserController@profileImage')->middleware('localization');

        Route::resource('useraddress','UserAddressController')->middleware('localization');    
        Route::get('my-order','OrderController@index')->middleware('localization');
        Route::get('my-order/{order_key}','OrderController@show')->middleware('localization');
        Route::get('my-order-reorder','OrderController@reOrder')->middleware('localization');
        Route::post('calculate-data','OrderController@calculateData')->middleware('localization');
        Route::post('place-order','OrderController@placeOrder')->middleware('localization');        

        Route::post('cart-update','CartController@userCart')->middleware('localization');
        Route::post('cart-quantity-update','CartController@updateQuantity')->middleware('localization');
        Route::get('cart-view','CartController@getCart')->middleware('localization');
        Route::get('cart-clear','CartController@clearCart')->middleware('localization');

        Route::get('loyalty-point','LoyaltyPointController@loyaltyPointDetails')->middleware('localization');

    });
    Route::get('order-confirmation-mail/{order_key}','OrderController@sendConfirmationMail')->middleware('localization');


    Route::group(['prefix' => 'vendor', 'namespace' => 'Vendor'],function()
    {
        Route::post('login','AuthController@login')->middleware('localization');  

        Route::group(['middleware' => ["auth:".GUARD_OUTLET_API.",".GUARD_VENDOR_API]],function()
        {
            Route::get('logout','AuthController@logout')->middleware('localization');
            Route::match(['GET','PUT'],'profile','VendorController@profile')->middleware('localization');
            Route::put('change-branch-status','VendorController@changeBranchStatus')->middleware('localization');
            Route::get('incoming-orders','OrderController@incomingOrders')->middleware('localization');
            Route::get('accepted-orders','OrderController@acceptedOrders')->middleware('localization');
            Route::get('view-order/{order_key}','OrderController@showOrder')->middleware('localization');
            Route::put('order-change-status/{order_key}','OrderController@changeStatus')->middleware('localization');            
            Route::get('orders-report','OrderController@report')->middleware('localization');
            Route::get('branch-status','VendorController@BranchStatus')->middleware('localization');
        });

    });


    Route::fallback(function () {
        $response = ['status' => 'fail', 'code' => HTTP_NOT_FOUND, 'message' => 'URL Not Found.', 'time'=> time()];
        return response()->json($response,HTTP_NOT_FOUND);
    });

});




    