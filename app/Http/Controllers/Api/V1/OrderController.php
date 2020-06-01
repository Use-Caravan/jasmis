<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\{
    Controllers\Api\V1\Controller,
    Controllers\Api\V1\CartController,
    Resources\Api\V1\OrderResource,
    Resources\Api\V1\CalculateResource
};
use App\Mail\OrderConfirmation;
use App\Helpers\{
    OneSignal,
    Curl,
    SadadPaymentGateway
};
use App\Api\{
    Cart,
    CartItem,
    Country,    
    CountryLang,    
    City,
    CityLang,    
    Area,    
    AreaLang,    
    Branch,
    BranchLang,
    BranchTimeslot,    
    DeliveryCharge,
    IngredientGroupLang,
    IngredientGroup,
    Ingredient,
    IngredientLang,
    IngredientGroupMapping,    
    Item,
    ItemLang,
    UserAddress,
    User,    
    Voucher,
    VoucherBeneficiary,
    VoucherUsage,
    LoyaltyPoint,
    Vendor,
    VendorLang,
    Order,
    OrderItem,
    OrderItemLang,
    OrderItemIngredientGroup,
    OrderItemIngredientGroupLang,
    OrderIngredient,
    ItemGroupMapping,
    OrderIngredientLang,
    UserLoyaltyCredit,
    BranchDeliveryArea,
    DeliveryArea
};


use App\ {
    CorporateOffer,
    CorporateVoucher,
    UserCorporate,
    CorporateVoucherItem
};

use App\{
    Transaction,
    PaymentGateway
};

use App;
use Auth;
use Common;
use DB;
use FileHelper;
use Mail;
use Schema;
use Storage;
use Validator;

class OrderController extends Controller
{
    public  $userDetails,
            $branchDetails,           
            $orderDetails,           
            $branchDeliveryArea,
            $userAddress,
            $cartDetails,
            $voucherDetails,
            $cartQuantity,
            $userCorporate;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {           
        $orders = Order::getCustomerOrders()
            ->where([Order::tableName().'.user_id' => request()->user()->user_id])->get();
        $orders = OrderResource::collection($orders);        
        $this->setMessage( __('apimsg.Orders are fetched') );
        return $this->asJson($orders);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
             
        $orders = Order::getCustomerOrders()->first();
        if($orders === null){
            return $this->commonError( __('apimsg.Invalid order') );
        }
        $orders = new OrderResource($orders);   
        $this->setMessage( __('apimsg.Orders are fetched') );
        return $this->asJson($orders);
    }


    /**
     * Place order
     */
    public function placeOrder()
    {      
        $rules = [
            'branch_key'    => 'required|exists:branch,branch_key',
            'user_address_key'    => 'nullable|required_if:order_type,1|exists:user_address,user_address_key',
            'coupon_code'    => 'nullable',
            'order_type'    => 'nullable|numeric',
            'payment_option'    => 'nullable|numeric',
            'delivery_date' => 'nullable|required_if:asap,0',
            'delivery_time' => 'nullable|required_if:asap,0',
            'asap'    => 'nullable|numeric',                        
        ];                       

        $validator = Validator::make(request()->all(),$rules);        
        if($validator->fails()) {
            return $this->validateError($validator->errors());            
        }   
        $this->cartDetails = Cart::where(['user_id' => request()->user()->user_id])->first();
        $responseData = $this->checkoutQuotation(true);
        
        if($responseData['status'] === false && $responseData['type'] === EXPECTATION_FAILED) {
            return $this->commonError($responseData['error']);
        }
        if($responseData['status'] === false && $responseData['type'] === HTTP_UNPROCESSABLE) {
            return $this->prepareResponse();
        }         
        $paymentDetails = $responseData['data'];                        
        $deliveryboyData = [];
        DB::beginTransaction();
        try{ 

            if(request()->corporate_voucher !== true) {
                if($paymentDetails['sub_total']['cprice'] < $this->branchDetails->vendor_min_order_value) {                
                    return $this->commonError( __("apimsg.Order value should be greater than",['amount' => Common::currency($this->branchDetails->vendor_min_order_value)]) );
                }
            }

            $totalOrders = ((int)Order::count()) + 1;
            $orderNumber = str_repeat('0', max(0, 7 - strlen($totalOrders))) . $totalOrders;
            $orderDateTime = date('Y-m-d H:i:s');
            $adminProfit = ((($paymentDetails['total']['cprice'])/100)*($this->branchDetails->vendor_commission));

            $userCorporateID = null;
            if(request()->user()->user_type === USER_TYPE_CORPORATES) {
                $userCorporate = UserCorporate::where(['office_email' => $this->userDetails->email, 'is_booked' => 0])->first();
                if($userCorporate !== null) {
                    $userCorporateID = $userCorporate->user_corporate_id;
                    $this->userCorporate = $userCorporate;
                }
            }

            $claim_corporate_offer_booking = 0;
            if(request()->corporate_voucher === true) {
                $claim_corporate_offer_booking = 1;
            }
            $corporate_voucher_code = '';
            if(request()->corporate_voucher_code !== null) {
                $corporate_voucher_code = request()->corporate_voucher_code;
            }

            $fillables = [                
                'order_number'  => $orderNumber,
                'order_booked_by' => request()->user()->user_type === USER_TYPE_CUSTOMER ? USER_TYPE_CUSTOMER : USER_TYPE_CORPORATES,
                'user_corporate_id' => $userCorporateID,
                'claim_corporate_offer_booking' => $claim_corporate_offer_booking,
                'corporate_voucher_code' => $corporate_voucher_code,
                'vendor_id'  => $this->branchDetails->vendor_id,
                'branch_id'  => $this->branchDetails->branch_id,
                'user_id'  => $this->userDetails->user_id,
                'cart_id'  => $this->cartDetails->cart_id,
                'user_address_id'  => ($this->userAddress === null) ? '' : $this->userAddress->user_address_id,
                'user_email'  => $this->userDetails->email,
                'user_phone_number'  => $this->userDetails->phone_number,
                'order_datetime'  => $orderDateTime,
                'order_type'  => request()->order_type,
                'delivery_type'  => (request()->asap == 1) ? request()->asap : 2,
                'payment_type'  => request()->payment_option,
                'item_total'  => $paymentDetails['sub_total']['cprice'],
                'delivery_fee'  => (isset($paymentDetails['delivery_cost']['cprice'])) ? $paymentDetails['delivery_cost']['cprice'] : 0,
                'delivery_distance'  => (isset($paymentDetails['delivery_cost']['delivery_distance'])) ? $paymentDetails['delivery_cost']['delivery_distance'] : 0,
                'tax'  => $paymentDetails['vat_tax']['cprice'],
                'tax_percent'  => $paymentDetails['vat_tax']['percent'],
                'service_tax'  => isset($paymentDetails['service_tax']['cprice']) ? $paymentDetails['service_tax']['cprice'] : 0,
                'service_tax_percent'  => isset($paymentDetails['service_tax']['percent']) ? $paymentDetails['service_tax']['percent'] : 0,
                'voucher_id'  => ($this->voucherDetails === null) ? null : $this->voucherDetails->voucher_id,
                'voucher_offer_value'  => isset($paymentDetails['voucher_details']['cprice']) ? $paymentDetails['voucher_details']['cprice'] : 0,
                'order_total'  => $paymentDetails['total']['cprice'],
                'order_message'  => request()->order_notes,
                'vendor_commission' => $this->branchDetails->vendor_commission,
                'admin_profit' => $adminProfit,
                'vendor_profit' => (($paymentDetails['total']['cprice']) - $adminProfit),
                'delivery_datetime' => (request()->asap == 1) ? date('Y-m-d H:i:s') : request()->delivery_date." ". date('H:i:s', strtotime(request()->delivery_time)),
                'order_status' => ORDER_APPROVED_STATUS_PENDING,
                'status' => ITEM_ACTIVE,
            ];

            $order = new Order();
            $order = $order->fill($fillables);
            $order->save();
            $orderID = $order->getKey();
            $orderKey = $order->order_key;
            $this->orderDetails = $order;
            DB::commit();            
        } catch(Exception $e) {
            DB::rollback();
            throw $e->getMessage();
        }
        DB::beginTransaction();
        try {
            $userDetails = $this->userDetails;                                                          
            $branchDetails = Branch::where(['branch_key' => request()->branch_key]);
            BranchLang::selectTranslation($branchDetails);
            $branchDetails = $branchDetails->first();
            $responseData = ['branch_logo' => FileHelper::loadImage($branchDetails->branch_logo),'contact_email' => $order->user_email,'order_key' => $order->order_key,'order_number' => config('webconfig.app_inv_prefix').$order->order_number, 'order_total' => Common::currency($order->order_total)];
            $countItems = 0;
            foreach($paymentDetails['items'] as $value) {
                
                /** Order Item and Item Lang */
                $orderItem = new OrderItem();
                $items = [
                    'order_id' => $orderID,
                    'item_id' => $value['item_id'],
                    'base_price' => $value['cprice'],
                    'item_quantity' => $value['quanity'],
                    'item_total_price' => $value['cprice'] * $value['quanity'],
                    'item_subtotal' => $value['csubtotal'],
                    'item_instruction' => isset($value['item_instruction']) ? $value['item_instruction'] : '',
                ];
                $orderItem = $orderItem->fill($items);
                $orderItem->save();
                $orderItemID = $orderItem->getKey();                

                if(request()->user()->user_type === USER_TYPE_CORPORATES) {

                    for($voucherQty = 0; $voucherQty < (int)$value['quanity']; $voucherQty++) {

                        $coporateVoucher = new CorporateVoucher();
                        $coporateVoucher = $coporateVoucher->fill([
                            'voucher_number' => rand(1000,9999),
                            'order_id' => $orderID,
                            'user_corporate_id' => $this->userCorporate->user_corporate_id
                        ]);
                        $coporateVoucher->save();
                        $coporateVoucherItem = new CorporateVoucherItem();
                        $coporateVoucherItem = $coporateVoucherItem->fill([
                            'corporate_voucher_id' => $coporateVoucher->getKey(),
                            'order_item_id' => $orderItemID,
                            'quantity' => 1,
                            'is_claimed' => 0
                        ]);
                        $coporateVoucherItem->save();
                    }
                }

                $itemLang = ItemLang::where('item_id',$value['item_id'])->get();
                foreach($itemLang as $ILvalue) {

                    $item_path = FileHelper::copyFile($ILvalue->item_image,ORDER_ITEM_PATH);
                    $orderItemLang = new OrderItemLang();
                    $orderItemLang = $orderItemLang->fill([
                    'order_item_id' => $orderItemID,
                    'language_code' => $ILvalue->language_code,
                    'item_name' => $ILvalue->item_name,
                    'item_description' => $ILvalue->item_description,
                    'item_image_path' => $item_path
                    ]);
                    $orderItemLang->save();
                }
                /** Order Item and Item Lang */

                /** Order Item Ingredient Group and Ingredient Group  Lang */
                foreach($value['ingredient_groups'] as $IGValue) {

                    $orderingredientGroup = new OrderItemIngredientGroup();
                    $ingredientGroupDetails = IngredientGroup::find($IGValue['ingredient_group_id']);
                    $orderingredientGroup = $orderingredientGroup->fill([
                        'order_id' => $orderID,
                        'order_item_id' => $orderItemID,
                        'ingredient_group_id' => $IGValue['ingredient_group_id'],
                        'ingredient_type' => $ingredientGroupDetails->ingredient_type,
                        'minimum' => $ingredientGroupDetails->minimum,
                        'maximum' => $ingredientGroupDetails->maximum,
                        'ingredient_group_subtotal' => $IGValue['ingredient_group_csubtotal']
                    ]);
                    $orderingredientGroup->save();
                    $orderingredientGroupID = $orderingredientGroup->getKey();
                    $ingredientGroupLang = IngredientGroupLang::where('ingredient_group_id',$IGValue['ingredient_group_id'])->get();
                    foreach($ingredientGroupLang as $IGroupLang) {
                        $orderIngredientGropuLang = new OrderItemIngredientGroupLang();
                        $orderIngredientGropuLang = $orderIngredientGropuLang->fill([
                            'order_item_ingredient_group_id' => $orderingredientGroupID,
                            'language_code' => $IGroupLang->language_code,
                            'group_name' => $IGroupLang->ingredient_group_name,
                        ]);
                        $orderIngredientGropuLang->save();
                    }
                    /** Order Item Ingredient Group and Ingredient Group  Lang */

                    /** Order Item Ingredient and Ingredient Lang */
                    $deliveryboyItems[$countItems]['ingredients'] = [];
                    foreach($IGValue['ingredients'] as $Ivalue) {


                        $ingredientDetails = Ingredient::find($Ivalue['ingredient_id']);
                        $orderIngredient = new OrderIngredient();
                        $orderIngredient = $orderIngredient->fill([
                            'order_id' => $orderID,
                            'order_item_id' => $orderItemID,
                            'order_item_ingredient_group_id' => $orderingredientGroupID,
                            'ingredient_id' => $Ivalue['ingredient_id'],
                            'ingredient_price' => $Ivalue['cprice'],
                            'ingredient_quanitity' => $Ivalue['quantity'],
                            'ingredient_subtotal' => $Ivalue['ingredient_csubtotal']
                        ]);
                        $orderIngredient->save();
                        $orderIngredientID = $orderIngredient->getKey();
                        $ingredientLangDetails = IngredientLang::where('ingredient_id',$Ivalue['ingredient_id'])->get();                        

                        foreach($ingredientLangDetails as $ingredientLang) {
                            $orderIngredientLang = new OrderIngredientLang();
                            $orderIngredientLang = $orderIngredientLang->fill([
                                'order_ingredient_id' => $orderIngredientID,
                                'language_code' => $ingredientLang->language_code,
                                'ingredient_name' => $ingredientLang->ingredient_name
                            ]);                            
                            $orderIngredientLang->save();
                            $deliveryboyItems[$countItems]['ingredients']['name'] = $ingredientLang->ingredient_name;
                        }
                    }
                }
                $countItems++;
               /*  $responseData = ['branch_logo' => FileHelper::loadImage($branchDetails->branch_logo),'contact_email' => $order->user_email,'order_key' => $order->order_key, 'order_number' => config('webconfig.app_inv_prefix').$order->order_number, 'order_total' => Common::currency($order->order_total),
                             'tax' => Common::currency($order->tax),'subtotal' => Common::currency($orderItem->item_subtotal),'shipping' => Common::currency($paymentDetails['delivery_cost']['cprice']),'order_datetime' => $order->order_datetime,
                             'item_name' => $orderItemLang->item_name
                ]; */
            }
              
            $user = User::find($this->userDetails->user_id);
            $deviceToken = $user->device_token;                    

            if(request()->user()->user_type == USER_TYPE_CUSTOMER) {
                $this->sendConfirmationMail($order->order_key);
            }

            /* if(request()->user()->user_type === USER_TYPE_CORPORATES) {
                $order = Order::findByKey($order->order_key)->first();
                if($order->order_status === ORDER_APPROVED_STATUS_DELIVERED) {
                    $this->sendConfirmationMail($order->order_key);
                }
            } */
            
            $responseData['payment_mode'] = request()->payment_option;
            $responseData['payment_url'] = '';   

            if(request()->user()->user_type === USER_TYPE_CUSTOMER) {
                if(request()->coupon_code != null && request()->coupon_code != "") {
                    $voucher = Voucher::where('promo_code',request()->coupon_code)->first();
                    $voucherUsage = new VoucherUsage();
                    $voucherUsage->voucher_id  = $voucher->voucher_id;
                    $voucherUsage->beneficiary_type = $voucher->apply_promo_for; 
                    
                    if($voucher->promo_for_shops == PROMO_SHOPS_PARTICULAR) {
                        $voucherUsage->beneficiary_id = $this->branchDetails->branch_id;
                    }
                    else if($voucher->promo_for_user == PROMO_USER_PARTICULAR) {
                        $voucherUsage->beneficiary_id = $userDetails->user_id;
                    }
                    else if($voucher->promo_for_shops == PROMO_SHOPS_ALL || $voucher->promo_for_user == PROMO_USER_ALL){
                        $voucherUsage->beneficiary_id = $userDetails->user_id;
                    }
                    $voucherUsage->used_date = $order->order_datetime;
                    $voucherUsage->order_id = $order->order_id;
                    if($order->payment_type == PAYMENT_OPTION_COD){
                        $voucherUsage->status = ITEM_ACTIVE;
                    }
                    $voucherUsage->save(); 
                } 
            }
            
            switch(request()->payment_option) {
                case PAYMENT_OPTION_ONLINE:
                    $payableAmount = $paymentDetails['total']['cprice'];
                    if(request()->corporate_voucher) {
                        $payableAmount = $paymentDetails['total']['cprice'] - $paymentDetails['sub_total']['cprice'];
                    }
                    
                    $response = SadadPaymentGateway::instance()
                    ->setAmount($payableAmount)
                    ->setCustomerName($this->userDetails->first_name)
                    ->setCustomerMail($this->userDetails->email)
                    ->setCustomerPhone($this->userDetails->phone_number);
                    if(request()->is_web !== null) {
                        $response = $response->setRequestFrom(request()->is_web);
                    }
                    $response = $response->makePayment();
                    
                    
                    if($response !== null) {

                        $paymentData = [
                            'customer_name' => $this->userDetails->first_name,
                            'customer_email' => $this->userDetails->email,
                            'customer_phone_number' => $this->userDetails->phone_number,
                            'price' => $payableAmount,
                        ];

                        $paymentGateway = new PaymentGateway();                    
                        $paymentGateway = $paymentGateway->fill([
                            'sent_data' => json_encode($paymentData),
                            'gateway_url' => $response['payment-url'],
                            'received_data' => json_encode($response)
                        ]);
                        $paymentGateway->save();                                        

                        $paymentGatewayID = $paymentGateway->getKey();

                        $transactionData = [
                            'payment_gateway_id' => $paymentGatewayID,
                            'user_id' => $this->userDetails->user_id,
                            'transaction_for' => TRANSACTION_FOR_ONLINE_BOOKING,
                            'transaction_type' => TRANSACTION_TYPE_DEBIT,
                            'amount' => $payableAmount,
                            'transaction_number' => $response['transaction-reference'],
                            'status' => TRANSACTION_STATUS_PENDING
                        ];
                        $transaction = new Transaction();
                        $transaction = $transaction->fill($transactionData);
                        $transaction->save();
                        $responseData['payment_url'] =  $response['payment-url'];
                        $order = Order::find($orderID);
                        $order->transaction_id = $transaction->getKey();
                        $order->save();
      
                    }
                break;

                case PAYMENT_OPTION_COD:

                
                    (new CartController())->clearCart();

                    if(request()->corporate_voucher === true) {
                        
                        $corporateOffer = CorporateVoucher::where(['corporate_voucher_key' => request()->corporate_voucher_code ])->first();                        
                        if($corporateOffer !== null) {
                            $corporateVoucherItem = CorporateVoucherItem::where(['corporate_voucher_id' => $corporateOffer->corporate_voucher_id])->first();
                            $corporateVoucherItem->is_claimed = 1;
                            $corporateVoucherItem->claimed_at = date('Y-m-d H:i:s');
                            $corporateVoucherItem->save();
                        }
                    }
                    
                    $vendor = Vendor::find($this->branchDetails->vendor_id); 
                    $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Notification'], ['en' => 'Order placed successfully.'], [$deviceToken], []);
                    $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$this->branchDetails->device_token], []);
                    
                    if($vendor->web_app_id !== null) {
                        $oneSignalVendorWeb  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_WEB_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendor->web_app_id], []);
                    } 
                
                    if(!$oneSignalCustomer || !$oneSignalVendor) {
                        $this->commonError(__("apimsg.Notification not send") );
                    }

                break;

                case PAYMENT_OPTION_WALLET:
                
                    if(request()->corporate_voucher === true) {
                        $corporateOffer = CorporateVoucher::where(['corporate_voucher_key' => request()->corporate_voucher_code ])->first();
                        if($corporateOffer !== null) {
                            $corporateVoucherItem = CorporateVoucherItem::where(['corporate_voucher_id' => $corporateOffer->corporate_voucher_id])->first();
                            $corporateVoucherItem->is_claimed = 1;
                            $corporateVoucherItem->claimed_at = date('Y-m-d H:i:s');
                            $corporateVoucherItem->save();
                        }
                    }
                    $transactionData = [
                        'user_id' => $this->userDetails->user_id,
                        'transaction_for' => TRANSACTION_FOR_WALLET_BOOKING,
                        'transaction_type' => TRANSACTION_TYPE_DEBIT,
                        'amount' => $paymentDetails['total']['cprice'],
                        'transaction_number' => Common::generateRandomString(Transaction::tableName(), 'transaction_number', $length = 32),
                        'status' => TRANSACTION_STATUS_SUCCESS
                    ];


                    $user = User::find($this->userDetails->user_id);

                    if($user->wallet_amount < $paymentDetails['total']['cprice']){

                        $payableAmount = $paymentDetails['total']['cprice'] - $user->wallet_amount;
                        
                        
                        $response = SadadPaymentGateway::instance()
                        ->setAmount($payableAmount)
                        ->setCustomerName($this->userDetails->first_name)
                        ->setCustomerMail($this->userDetails->email)
                        ->setCustomerPhone($this->userDetails->phone_number);
                        if(request()->is_web !== null) {
                            $response = $response->setRequestFrom(request()->is_web);
                        }
                        $response = $response->makePayment();
                        
                        
                        if($response !== null) {

                            $paymentData = [
                                'customer_name' => $this->userDetails->first_name,
                                'customer_email' => $this->userDetails->email,
                                'customer_phone_number' => $this->userDetails->phone_number,
                                'price' => $payableAmount,
                            ];

                            $paymentGateway = new PaymentGateway();                    
                            $paymentGateway = $paymentGateway->fill([
                                'sent_data' => json_encode($paymentData),
                                'gateway_url' => $response['payment-url'],
                                'received_data' => json_encode($response)
                            ]);
                            $paymentGateway->save();                                        

                            $paymentGatewayID = $paymentGateway->getKey();

                            $transactionData = [
                                'payment_gateway_id' => $paymentGatewayID,
                                'user_id' => $this->userDetails->user_id,
                                'transaction_for' => TRANSACTION_FOR_ONLINE_BOOKING,
                                'transaction_type' => TRANSACTION_TYPE_DEBIT,
                                'amount' => $payableAmount,
                                'transaction_number' => $response['transaction-reference'],
                                'status' => TRANSACTION_STATUS_PENDING
                            ];
                            $transaction = new Transaction();
                            $transaction = $transaction->fill($transactionData);
                            $transaction->save();
                            $responseData['payment_url'] =  $response['payment-url'];
                            $order = Order::find($orderID);
                            $order->transaction_id = $transaction->getKey();
                            $order->payment_type = PAYMENT_OPTION_WALLET_AND_ONLINE;
                            $order->wallet_amount_used = $user->wallet_amount;
                            $order->save();
          
                        }
                    }else{

                        $vendor = Vendor::find($this->branchDetails->vendor_id);
                        $transaction = new Transaction();
                        $transaction = $transaction->fill($transactionData);
                        $transaction->save();
                        
                        $user->wallet_amount = $user->wallet_amount - $paymentDetails['total']['cprice'];
                        $user->save();
                        $order = Order::find($orderID);
                        $order->transaction_id = $transaction->getKey();
                        $order->payment_status = ORDER_PAYMENT_STATUS_SUCCESS;
                        $order->save();
                        (new CartController())->clearCart();

                        if($order->payment_status = ORDER_PAYMENT_STATUS_SUCCESS) {
                            $voucherUsageStatus = VoucherUsage::where(['order_id' => $order->order_id])->first();
                                if($voucherUsageStatus !== null) {
                                    $voucherUsageStatus->status = ITEM_ACTIVE;
                                    $voucherUsageStatus->save(); 
                                }
                        }

                        $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Notification'], ['en' => 'Order placed successfully.'], [$deviceToken], []);
                        $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$this->branchDetails->device_token], []);
                        if($vendor->web_app_id !== null) {
                            $oneSignalVendorWeb  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_WEB_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendor->web_app_id], []);
                        } 
                        if(!$oneSignalCustomer || !$oneSignalVendor) {
                            $this->commonError(__("apimsg.Notification not send") );
                        }
                    }
                break;
                
                case CORPORATE_BOOKING_PAYMENT_ONLINE:
                    $payableAmount = $paymentDetails['total']['cprice'];
                    if(request()->corporate_voucher) {
                        $payableAmount = $paymentDetails['total']['cprice'] - $paymentDetails['sub_total']['cprice'];
                    }
                    
                    $corporateUserDetails = User::select(User::tableName().'.user_id',UserCorporate::tableName().'.*')
                                            ->leftjoin(UserCorporate::tableName(),User::tableName().'.email',UserCorporate::tableName().'.office_email')
                                            ->where([UserCorporate::tableName().'.office_email' => $this->userDetails->email,UserCorporate::tableName().'.is_booked' => 0])
                                            ->orderBy(UserCorporate::tableName().'.user_corporate_id','desc')
                                            ->first();
                    
                    $response = SadadPaymentGateway::instance()
                    ->setAmount($payableAmount)
                    ->setCustomerName($corporateUserDetails->corporate_name)
                    ->setCustomerMail($corporateUserDetails->office_email)
                    ->setCustomerPhone($corporateUserDetails->mobile_number)
                    ->setDescription($corporateUserDetails->voucher_description);
                    if(request()->is_web !== null) {
                        $response = $response->setRequestFrom(request()->is_web);
                    }
                    $response = $response->makePayment();
                    if($response !== null) {
                            
                        $paymentData = [
                            'customer_name' => $corporateUserDetails->corporate_name,
                            'customer_email' => $corporateUserDetails->office_email,
                            'customer_phone_number' => $corporateUserDetails->mobile_number,
                            'price' => $payableAmount,
                        ];
                        
                        $paymentGateway = new PaymentGateway();                    
                        $paymentGateway = $paymentGateway->fill([
                            'sent_data' => json_encode($paymentData),
                            'gateway_url' => $response['payment-url'],
                            'received_data' => json_encode($response)
                        ]);
                        
                        $paymentGateway->save();                                        

                        $paymentGatewayID = $paymentGateway->getKey();

                        $transactionData = [
                            'payment_gateway_id' => $paymentGatewayID,
                            'user_id' => $corporateUserDetails->user_id,
                            'transaction_for' => TRANSACTION_FOR_ONLINE_BOOKING,
                            'transaction_type' => TRANSACTION_TYPE_DEBIT,
                            'amount' => $payableAmount,
                            'transaction_number' => $response['transaction-reference'],
                            'status' => TRANSACTION_STATUS_PENDING
                        ];
                        $transaction = new Transaction();
                        $transaction = $transaction->fill($transactionData);
                        $transaction->save();
                        $responseData['payment_url'] =  $response['payment-url'];
                        $order = Order::find($orderID);
                        $order->transaction_id = $transaction->getKey();
                        $order->save();
                    } 
                    if($order) {
                        $this->userCorporate->is_booked = 1;
                        $this->userCorporate->order_id = $orderID;
                        $this->userCorporate->save();
                    }
                break; 
                case CORPORATE_BOOKING_PAYMENT_LPO:
                    $this->userCorporate->is_booked = 1;
                    $this->userCorporate->order_id = $orderID;
                    $this->userCorporate->save();
                    break;
                case CORPORATE_BOOKING_PAYMENT_CREDIT:
                    (new CartController())->clearCart();
                    $this->userCorporate->is_booked = 1;
                    $this->userCorporate->order_id = $orderID;
                    $this->userCorporate->save();
                    break;
            }
            DB::commit();
            /*
            This code not needed this functionality
            if(request()->order_type == ORDER_TYPE_DELIVERY) {
                $deliveryboyResult = $this->saveOrderOnDeliveryBoy($orderKey);
                $response = Common::compressData($deliveryboyResult);            
                if($response->status != HTTP_SUCCESS) {
                    return $this->commonError("Order place successfully. But delivery boy order not placed");
                }
            } */
            $this->setMessage(__('apimsg.Order placed successfully') );
            return $this->asJson($responseData);
        } catch(Exception $e) {
            DB::rollback();
            throw $e->getMessage();
        }                
    }

    public function sendConfirmationMail($orderKey)
    {   
        
        request()->request->add([
            'order_key' => $orderKey,            
        ]);
        
        $orderItemsDetails = $this->show($orderKey);
        $orderItemDetails = Common::compressData($orderItemsDetails);
        
        $orderDetails = Order::getList()
                       ->addSelect([User::tableName().'.email',
                                    User::tableName().'.first_name',
                                    User::tableName().'.last_name',    
                                    UserAddress::tableName().'.address_line_one',
                                    UserAddress::tableName().'.address_line_two',
                                    UserAddress::tableName().'.landmark',
                                    UserAddress::tableName().'.company'
                       ])
                       ->leftjoin(User::tableName(),Order::tableName().'.user_id',User::tableName().'.user_id')
                       ->leftjoin(UserAddress::tableName(),Order::tableName().'.user_address_id',UserAddress::tableName().'.user_address_id')
                       ->where([Order::tableName().'.order_key' => $orderKey])->first();
        
        $responseData = [
                'orderitems' => $orderItemDetails,
                'orderdetails' => $orderDetails,
                'need_voucher_url' => ''
            ];
            
        /* if(request()->corporate_voucher === true) {
            $responseData['need_voucher_url'] = route('frontend.download-vouchers',['order_key' => $orderKey]);
        } */
        if($orderDetails->order_booked_by === USER_TYPE_CORPORATES) {
            $responseData['need_voucher_url'] = route('frontend.download-vouchers',['order_key' => $orderKey]);
        }
        if($orderDetails->email !==  null && $orderDetails->email !== '')  {
            try {
                Mail::to($orderDetails->email)->send(new OrderConfirmation($responseData));           
             } catch (\Exception $ex) {            
                return response()->json(['status' => HTTP_UNPROCESSABLE, 'message' => __("apimsg.Mail configuration is incorrect")],HTTP_UNPROCESSABLE);
            }
        }
        return response()->json(['status' => HTTP_SUCCESS,'message' => __('apimsg.Mail has been sent.')],HTTP_SUCCESS);
    }

    public function calculateData()
    {       
        $rules = [
            'branch_key'    => 'required|exists:branch,branch_key',
            'user_address_key'    => 'nullable|exists:user_address,user_address_key',
            'coupon_code'    => 'nullable',
            'order_type'    => 'nullable|numeric',
            'payment_option'    => 'nullable|numeric',
            'delivery_date' => 'nullable|required_if:asap,0',
            'delivery_time' => 'nullable|required_if:asap,0',
            'asap'    => 'nullable|numeric',
        ];
        
        $validator = Validator::make(request()->all(),$rules);        
        if($validator->fails()) {
            return $this->validateError($validator->errors());            
        }
        $this->cartDetails = Cart::where(['user_id' => request()->user()->user_id])->orderBy('cart_id','DESC')->first();
        $responseData = $this->checkoutQuotation();
        if($responseData['status'] === false && $responseData['type'] === EXPECTATION_FAILED) {
            return $this->commonError($responseData['error']);
        }
        if($responseData['status'] === false && $responseData['type'] === HTTP_UNPROCESSABLE) {
            return $this->prepareResponse();
        }
        $this->setMessage(__("apimsg.Cart details are processed") );
        
        return $this->asJson($responseData['data']);
    }    

    /**     
     * @param boolean $isOrder set  ** true if you want place order **
     */
    public function checkoutQuotation($isOrder = false)
    {        
        $returnData = ['status' => false,'type' => EXPECTATION_FAILED,'error' => '','data' => []];
        $vendor  = new Vendor();                
        
        
        $userDetails = User::select('*')->where(['user_id' => request()->user()->user_id, 'status' => ITEM_ACTIVE ])->first();
        
        if($userDetails === null) {
            $returnData['error'] = __('apimsg.Invalid user or user has deactivated');
            return $returnData;
        }
        $this->userDetails = $userDetails;
                    
        $responseData['payment_details'] = [];
             
        /** Delivery time slot conditions */
        if(request()->asap == DELIVERY_TYPE_ASAP) {
            request()->request->add([
                'delivery_date' => date('Y-m-d'),
                'delivery_time' => date('H:i:s'),
            ]);
        }
        if(request()->delivery_date !== null && request()->delivery_time !== null) {

            /** Check the Order Time is available or not */
            $dayNumber = date("N", strtotime(request()->delivery_date));
            $timeSlot = Branch::select('*')
            ->leftJoin(BranchTimeslot::tableName(),Branch::tableName().".branch_id",'=',BranchTimeSlot::tableName().'.branch_id')
            ->where([
                BranchTimeslot::tableName().'.day_no' => $dayNumber,
                BranchTimeslot::tableName().'.status' => ITEM_ACTIVE,
                BranchTimeslot::tableName().'.timeslot_type' => request()->order_type,
                Branch::tableName().'.branch_key' => request()->branch_key,
            ])
            ->whereTime('start_time', '<', date('H:i:s', strtotime(request()->delivery_time)) )
            ->whereTime('end_time', '>', date('H:i:s', strtotime(request()->delivery_time)) )
            ->first();
            if($timeSlot === null) {
                $returnData['error'] = __('apimsg.Branch is not available for the selected time');
                return $returnData;
            }
        }        
        /** Delivery time slot conditions */

        /** Item Details */
        $cartDetails = $this->cartDetails;        

        if($cartDetails === null) {
            $returnData['error'] = __("apimsg.There is no items in your cart");            
            return $returnData;
        }
        
        $cartItem = CartItem::where('cart_id',$cartDetails->cart_id)->get();
        
        if($cartItem === null) {
            $returnData['error'] = __("apimsg.There is no items in your cart");
            return $returnData;                        
        }
        
        $itemArray['items'] = [];
        $this->cartQuantity = 0;
        foreach($cartItem as $key => $value) {
            
            $item = Item::find($value->item_id);            

            if($item === null) {
                
                continue;
            }



            $itemArray['items'][] = [
                'cart_item_key' => $value->cart_item_key,
                'item_key' => $item->item_key,
                'quantity' => $value->quantity,
                'ingrdient_groups' => json_decode($value->ingredients,true),
                'item_instruction' => $value->item_instruction
            ];
            $this->cartQuantity += $value->quantity;
        }                
        $itemDetails = $this->itemCheckoutItemData($itemArray);        
        
        

        if($itemDetails['status'] === false) {
            $returnData['error'] = $itemDetails['error'];
            return $returnData;
        } else {            
            $itemSubtotal = 0;
            foreach($itemDetails['data'] as $key => $value) {
                $itemSubtotal +=  $value['subtotal'];
                $itemDetails['data'][$key]['subtotal'] = Common::currency($value['subtotal']);
                $itemDetails['data'][$key]['csubtotal'] = $value['subtotal'];
            }
            $responseData['items'] = $itemDetails['data'];
            $subTotalDetails = [
                'name' => __('apimsg.Sub total'),
                'price' => Common::currency($itemSubtotal),
                'cprice' => $itemSubtotal,
                'color_code' => PAYMENT_SUB_TOTOAL_COLOR,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE,
            ];
            array_push($responseData['payment_details'], $subTotalDetails);
            $responseData['sub_total'] = $subTotalDetails;            
            
        }
        /** Item Details */

        /** Delivery charge and availability */
        $deliveryFeeAmount = 0;
        
        if(request()->order_type == ORDER_TYPE_DELIVERY && request()->user_address_key !== null) {
            $checkIfAvailable = $this->checkDeliveryAreaAvailable();                        
            
            /* if($checkIfAvailable['response'] !== null) {
                $checkIfAvailable = $checkIfAvailable['response']->toArray();                    
            } */
            if($checkIfAvailable['status'] === false) {
                $returnData['error'] = $checkIfAvailable['error'];
                return $returnData;
            } else {
                if(isset($checkIfAvailable['response'])) {
                        if(is_object($checkIfAvailable['response'])) {
                        $checkIfAvailable = $checkIfAvailable['response']->toArray();
                    }
                }    
             
                $deliveryFeeAmount = $checkIfAvailable['price'];
                                                        
                $deliveryDetails = [
                    'price' => Common::currency($deliveryFeeAmount),
                    'name' => __('apimsg.Delivery Fee',["km" => Common::round($checkIfAvailable['distance'],2)]), 
                    'cprice' => $deliveryFeeAmount, 
                    'delivery_distance' => Common::round($checkIfAvailable['distance'],2),
                    'color_code' => PAYMENT_DELIVERY_FEE_COLOR,
                    'is_bold' => IS_BOLD,
                    'is_italic' => IS_ITALIC,
                    'is_line' => IS_LINE,
                ];
                array_push($responseData['payment_details'], $deliveryDetails);
                $responseData['delivery_cost'] = $deliveryDetails;  
                          
            }
        } 
        /** Delivery charge and availability */         

        /** Vat and Service tax */
        $branchDetails = Branch::select([
            Vendor::tableName().'.vendor_key',
            Vendor::tableName().'.vendor_id',
            Vendor::tableName().'.payment_option',
            Vendor::tableName().'.username as vendor_username',
            Vendor::tableName().'.email as vendor_email',
            Vendor::tableName().'.mobile_number as vendor_mobile_number',
            Vendor::tableName().'.contact_number as vendor_contact_number',            
            Vendor::tableName().'.min_order_value as vendor_min_order_value',            
                        
            Vendor::tableName().'.country_id as vendor_country_id',
            Vendor::tableName().'.city_id as vendor_city_id',
            Vendor::tableName().'.area_id as vendor_area_id',                        
            Vendor::tableName().'.latitude as vendor_latitude',
            Vendor::tableName().'.longitude as vendor_longitude',
            Vendor::tableName().'.device_token as device_token',
            Vendor::tableName().'.tax as vendor_tax',
            Vendor::tableName().'.service_tax as vendor_service_tax',
            Vendor::tableName().'.commission as vendor_commission',
            Vendor::tableName().'.approved_status as vendor_approved_status',
            Vendor::tableName().'.status as vendor_status',

            Branch::tableName().'.branch_key',
            Branch::tableName().'.branch_id',
            Branch::tableName().'.order_type',
            Branch::tableName().'.contact_email as branch_contact_email',
            Branch::tableName().'.contact_number as branch_contact_number',
            Branch::tableName().'.restaurant_type as branch_restaurant_type',
            Branch::tableName().'.preparation_time as branch_preparation_time',
            Branch::tableName().'.delivery_time as branch_delivery_time',
            Branch::tableName().'.pickup_time as branch_pickup_time',
            Branch::tableName().'.country_id as branch_country_id',
            Branch::tableName().'.city_id as branch_city_id',
            Branch::tableName().'.area_id as branch_area_id',                        
            Branch::tableName().'.latitude as branch_latitude',
            Branch::tableName().'.longitude as branch_longitude',
        ])
        ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",'=',Vendor::tableName().'.vendor_id')
        ->where([
            Branch::tableName().'.status' => ITEM_ACTIVE,
            Vendor::tableName().'.status' => ITEM_ACTIVE,
            Branch::tableName().'.branch_key' => request()->branch_key,
        ])->first();
        if($branchDetails === null) {            
            $returnData['error'] = __('apimsg.Branch is not found');            
            return $returnData;
        }
        
        $this->branchDetails = $branchDetails;        

        $vatAmount = ($itemSubtotal * $branchDetails->vendor_tax) / 100;
        $vatDetails = [
            'name' => __('apimsg.VAT',['percent' => $branchDetails->vendor_tax]), 
            'price' => Common::currency($vatAmount), 
            'cprice' => $vatAmount, 
            'percent' => $branchDetails->vendor_tax,
            'color_code' => PAYMENT_VAR_TAX_COLOR,
            'is_bold' => IS_BOLD,
            'is_italic' => IS_ITALIC,
            'is_line' => IS_LINE, 
        ];
        array_push($responseData['payment_details'], $vatDetails);
        $responseData['vat_tax'] = $vatDetails;

        $serviceTaxAmount = 0;
        if($branchDetails->vendor_service_tax !== null && $branchDetails->vendor_service_tax > 0) {
            $serviceTaxAmount = ($itemSubtotal * $branchDetails->vendor_service_tax) / 100;
            $serviceTaxDetails = [
                'name' => __('apimsg.Service Tax',[ 'percent' => $branchDetails->vendor_service_tax]), 
                'price' => Common::currency($serviceTaxAmount), 
                'cprice' => $serviceTaxAmount, 
                'percent' => $branchDetails->vendor_service_tax,
                'color_code' => PAYMENT_SERVICE_TAX_COLOR,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE,
            ];
            array_push($responseData['payment_details'], $serviceTaxDetails);
            $responseData['service_tax'] = $serviceTaxDetails;
        }
        /** Vat and Service tax */

        $couponOfferPrice = 0;     
        if(request()->coupon_code != "" && request()->coupon_code != null) {            
            $voucherDetails = $this->voucherCalculation($itemSubtotal, true);

            if($voucherDetails['status'] === false) {
                $returnData['error'] = $voucherDetails['error'];
                return $returnData;
            }
            $voucherAmountDetails = [
                'name' => __('apimsg.Coupon Offer'), 
                'price' => Common::currency($voucherDetails['data']),
                'cprice' => $voucherDetails['data'],
                'color_code' => PAYMENT_COUPON_FEE_COLOR,
                'is_bold' => IS_BOLD,
                'is_italic' => IS_ITALIC,
                'is_line' => IS_LINE,
            ];
            $couponOfferPrice = $voucherDetails['data'];
            array_push($responseData['payment_details'], $voucherAmountDetails);
            $responseData['voucher_details'] = $voucherAmountDetails;
        }
        $totalCheckoutAmount = ($itemSubtotal - $couponOfferPrice) + $deliveryFeeAmount + $vatAmount + $serviceTaxAmount;
        $responseData['total'] = [
            'cprice' => $totalCheckoutAmount,
            'price' => Common::currency($totalCheckoutAmount),
            'name' => __('apimsg.Total'),
            'color_code' => PAYMENT_GRAND_TOTOAL_COLOR,
            'is_bold' => IS_BOLD,
            'is_italic' => IS_ITALIC,
            'is_line' => IS_LINE,
        ];   

        
        if(request()->user()->user_type === USER_TYPE_CUSTOMER) {

            /** Check Order type */
            if(request()->order_type !== null) {
                if(request()->order_type != $this->branchDetails->order_type && $this->branchDetails->order_type != ORDER_TYPE_BOTH) {
                    $returnData['error'] = __("apimsg.This delivery type is not available for this branch");
                    return $returnData;
                }                            
            }
            /** Check Order type */

            /** Check Payment Option condtions */          
            $branchPaymentOptions = explode(',',$this->branchDetails->payment_option);
            if(request()->payment_option !== null) {
                if(!in_array(request()->payment_option,$branchPaymentOptions)) {
                    $returnData['error'] = __("apimsg.Selected Payment type is not available for this branch");
                    return $returnData;
                }
                if(request()->payment_option == PAYMENT_OPTION_WALLET) {
                    if($this->userDetails->wallet_amount === null) {
                        $returnData['error'] = __("apimsg.You don't have wallet amount");
                        return $returnData;
                    }
                    if($this->userDetails->wallet_amount <= WALLET_ZERO ) {
                        $returnData['error'] = __("apimsg.Your wallet amount is too low to buy through c wallet");
                        return $returnData;
                    }
                }
            }
            /** Check Payment Option condtions */
        }


        $responseData = $this->dataFormat($responseData, $isOrder);
        return ['status' => true, 'data' => $responseData ];
    }


    public function itemCheckoutItemData($branchDetails)
    {        
        $items = [];
        foreach($branchDetails['items'] as $key => $value) {
            $itemDetails = Item::getItems($value['item_key'])->first();
            
            if($itemDetails === null) {
                $inactiveItem = Item::findByKey($value['item_key']);
                CartItem::where(['cart_item_key' => $value['cart_item_key'], 'item_id' => $inactiveItem->item_id ])->delete();
                continue;
            }
            if($itemDetails->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
                $itemPrice = $itemDetails->item_price - $itemDetails->offer_value;
            } else {                
                $itemPrice = $itemDetails->item_price - (($itemDetails->item_price/100) * $itemDetails->offer_value);
            }
            $itemSubTotal = (int)$value['quantity'] * (float)$itemPrice;
            $itemQuantity = $value['quantity'];
            $items[$key] = [
                'item_key' => $itemDetails->item_key,
                'item_id' => $itemDetails->item_id,
                'item_name' => $itemDetails->item_name,
                'item_image' => FileHelper::loadImage($itemDetails->item_image),
                'price' => Common::currency($itemPrice),
                'cprice' => $itemPrice,
                'quanity' => $value['quantity'],
                'subtotal' => $itemSubTotal,
                'ingredient_groups' => [],
                'ingredient_name' => "",
                'item_instruction' => isset($value['item_instruction']) ? $value['item_instruction'] : '',
            ];  
            if(isset($value['cart_item_key'])) {
                $items[$key]['cart_item_key'] = $value['cart_item_key'];
            }
            if(isset($value['ingrdient_groups']) && !empty($value['ingrdient_groups'])) {
                foreach($value['ingrdient_groups'] as $igKey => $igValue) {
                    $ingredientGroup = IngredientGroup::select(IngredientGroup::tableName().'.*');
                    IngredientGroupLang::selectTranslation($ingredientGroup);
                    $ingredientGroup = $ingredientGroup->where('ingredient_group_key',$igValue['ingredient_group_key'])->first();
                    if($ingredientGroup === null) {                    
                        return ['status'=> false, 'error' => __('apimsg.Invalid Ingredient group key')];
                    }
                    $items[$key]['ingredient_groups'][$igKey] = [
                        'ingredient_group_key' => $ingredientGroup->ingredient_group_key,
                        'ingredient_group_id' => $ingredientGroup->ingredient_group_id,
                        'ingredient_group_name' => $ingredientGroup->ingredient_group_name,                    
                    ];                
                    $ingredientGroupSubTotal = 0;                
                    if(!isset($igValue['ingredients'])) {
                        return ['status'=> false, 'error' => __('apimsg.Ingredients are missing')];
                    }
                    foreach($igValue['ingredients'] as $iKey => $iValue) {
                        $ingredients = IngredientGroupMapping::select(IngredientGroupMapping::tableName().".*",Ingredient::tableName().".*")
                        ->leftJoin(Ingredient::tableName(),IngredientGroupMapping::tableName().'.ingredient_id','=',Ingredient::tableName().'.ingredient_id');
                        IngredientLang::selectTranslation($ingredients);
                        $ingredients = $ingredients->where([
                            Ingredient::tableName().".ingredient_key" => $iValue['ingredient_key'],
                            Ingredient::tableName().".status" => ITEM_ACTIVE,
                            'ingredient_group_mapping.ingredient_group_id' => $ingredientGroup->ingredient_group_id
                            ])->first();
                        if($ingredients === null) {
                            return ['status'=> false, 'error' => 'Invalid Ingredient key'];
                        }
                        $ingredientSubtotal = (int)$iValue['quantity'] * ( (float)$ingredients->price * $itemQuantity) ;
                        $items[$key]['ingredient_name'] = (isset($items[$key]['ingredient_name']) && $items[$key]['ingredient_name'] != '') ? $items[$key]['ingredient_name'].", ".$ingredients->ingredient_name : $ingredients->ingredient_name;
                        $items[$key]['ingredient_groups'][$igKey]['ingredients'][$iKey] = [
                            'ingredient_key' => $ingredients->ingredient_key,
                            'ingredient_id' => $ingredients->ingredient_id,
                            'price' => Common::currency($ingredients->price),
                            'cprice' => (float)$ingredients->price,
                            'quantity' => $iValue['quantity'],
                            'ingredient_subtotal' => Common::currency($ingredientSubtotal),
                            'ingredient_csubtotal' => $ingredientSubtotal,
                        ];
                        $itemSubTotal += $ingredientSubtotal;
                        $ingredientGroupSubTotal += $ingredientSubtotal;
                    }

                    $items[$key]['ingredient_groups'][$igKey]['ingredient_group_subtotal'] = Common::currency($ingredientGroupSubTotal);
                    $items[$key]['ingredient_groups'][$igKey]['ingredient_group_csubtotal'] = $ingredientGroupSubTotal;
                                    
                    $items[$key]['subtotal'] = $itemSubTotal;
                }
            }
        }        
        return ['status'=> true, 'data' => $items];
    }
   
    public function checkDeliveryAreaAvailable()
    {
        $userAddress = UserAddress::select('user_address.*')
        ->where([
            'user_address_key' => request()->user_address_key,
            'status' => ITEM_ACTIVE,
        ])->first();
        $branch = Branch::findByKey(request()->branch_key);
        $branchZoneType = BranchDeliveryArea::select([
                BranchDeliveryArea::tableName().".branch_id",
                DeliveryArea::tableName().'.zone_type'                
            ])
            ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
            ->where([BranchDeliveryArea::tableName().'.branch_id' => $branch->branch_id])
            ->first();
            
        if($userAddress === null) {
            return ['status'=> false, 'error' => __('apimsg.User address not found')];
        }
        $this->userAddress = $userAddress;
        if($branchZoneType->zone_type == DELIVERY_AREA_ZONE_CIRCLE) {
            
            $branchDeliveryArea = Branch::select([
                    'branch.branch_id',
                    'branch.branch_key',
                    'DA.*',
                    DB::raw("( 6371000 * acos( cos( radians($userAddress->latitude) ) * cos( radians( DA.circle_latitude ) ) 
                    * cos( radians( DA.circle_longitude ) - radians($userAddress->longitude) ) + sin( radians($userAddress->latitude) )
                    * sin( radians( DA.circle_latitude ) ) ) ) as distance")
                ])
                ->leftJoin('branch_delivery_area as BDA','branch.branch_id','=','BDA.branch_id')
                ->leftJoin('delivery_area as DA','BDA.delivery_area_id','=','DA.delivery_area_id')
                ->where([
                    'branch.branch_key' => request()->branch_key,
                    'DA.status' => ITEM_ACTIVE,
                    "DA.zone_type" => DELIVERY_AREA_ZONE_CIRCLE,
                ])
                ->havingRaw("distance <  DA.zone_radius")
                ->orderBy('distance','ASC')
                ->first();
                
        }
        if($branchZoneType->zone_type == DELIVERY_AREA_ZONE_POLYGON) {
            
            // $zoneLatLng = '[GEOMETRY - 129 B]';
            $branchDeliveryArea = BranchDeliveryArea::select([
                BranchDeliveryArea::tableName().".branch_id",
                DeliveryArea::tableName().".*"                
            ])
            ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_POLYGON,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE
            ])
            ->whereNull(DeliveryArea::tableName().".deleted_at")
            ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(".$userAddress->latitude.", ".$userAddress->longitude."))")
            // ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(1.122, 21.2121))")
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")
            ->get();
            
        }
            
        if($branchDeliveryArea === null) {
            return ['status'=> false, 'error' => __('apimsg.The selected address in not within the delivery area of the branch')];
        }
        $this->branchDeliveryArea = $branchDeliveryArea;
        return $this->calculateDistanceFar($userAddress, request()->branch_key);
    }

    /**
     * @param object $userAddress
     * @param string $branchKey
     */
    public function calculateDistanceFar($userAddress, $branchKey)
    {   
        $deliveryChrage = DeliveryCharge::select([
            '*',
            DB::raw("(select 6371 * acos( cos( radians($userAddress->latitude) ) * cos( radians( branch.latitude ) ) 
            * cos( radians( branch.longitude ) - radians($userAddress->longitude) ) + sin( radians($userAddress->latitude) )
            * sin( radians( branch.latitude ) ) ) as distance from branch where branch.branch_key = '$branchKey' &&  branch.status = ".ITEM_ACTIVE.") as distance")
        ])->havingRaw('from_km <= distance && to_km >= distance')->where('status',ITEM_ACTIVE)->orderBy('price','ASC')->first();
       
        if($deliveryChrage === null) {
           return $deliveryChrage = [
                'delivery_charge_id' => null,
                'delivery_charge_key' => null,
                'from_km' => null,
                'to_km' => null,
                'price' => 0,
                'status' => null,
                'created_at' => null,   
                'updated_at' => null,
                'deleted_at' => null,
                'distance' => null
            ];
            
            // return ['status'=> true, 'response' => $deliveryChrage];
        } 
        return ['status'=> true, 'response' => $deliveryChrage];
    }
    
    /**     
     * @param double $itemSubtotal
     */
    public function voucherCalculation($itemSubtotal, $isOrder = false)
    {
        $response = ['status' => false, 'error' => '', 'data' => '' ];

        $voucherOffer = ['percent' => 0, 'price' => 0];

        if( request()->user()->user_type === USER_TYPE_CORPORATES ) {

            $offer = CorporateOffer::getList()->where(['corporate_offer_key' => request()->coupon_code])->where('status', ITEM_ACTIVE)->first();
            if($offer !== null) {
                if($offer->offer_type  === CORPORATE_OFFER_TYPE_QUANTITY) {
                    if($this->cartQuantity < $offer->offer_level) {
                        $error = __('apimsg.Your cart quantity should be greater than '.$offer->offer_level);
                        goto error;
                    }
                } else if($offer->offer_type  === CORPORATE_OFFER_TYPE_AMOUNT) {
                    if($itemSubtotal < $offer->offer_level) {
                        $error = __('apimsg.Your checkout amount should be greater than '.$offer->offer_level);
                        goto error;
                    }
                }
                $discountAmount = $itemSubtotal / 100 * $offer->offer_value;
                goto response;
            }            
            $error = __('apimsg.Offer not found');
            goto error;
        }

        $voucher = Voucher::select('*')->where('status', ITEM_ACTIVE)->where('promo_code',request()->coupon_code)->whereDate('expiry_date','>',date('Y-m-d H:i:s'))->first();
        if($voucher === null) {
            $error = __('apimsg.Voucher is unavailable');
            goto error;
        }
        $userDetails = $this->userDetails;
        $this->voucherDetails = $voucher;
        $branchDetails = $this->branchDetails;
        $orderDetails = $this->orderDetails;
        switch($voucher->apply_promo_for) {
            case VOUCHER_APPLY_PROMO_SHOPS:
                /** if($voucher->promo_for_shops == PROMO_SHOPS_ALL) No need to check all condition */
                if($voucher->promo_for_shops == PROMO_SHOPS_PARTICULAR) {
                    $shopIsAvailable = VoucherBeneficiary::select('beneficiary_id')->where(['beneficiary_type' => VOUCHER_APPLY_PROMO_SHOPS,'voucher_id'=>$voucher->voucher_id,'beneficiary_id' => $branchDetails->branch_id])->count();
                    if( (int)$shopIsAvailable === 0) {                        
                        $error = __('apimsg.Voucher is unavailable for this shop');
                        goto error;
                    }                    
                }
                
                if($voucher->limit_of_use != 0) {
                    $usageCount = VoucherUsage::select('*')->where(['beneficiary_type' => VOUCHER_APPLY_PROMO_SHOPS,'voucher_id'=>$voucher->voucher_id,'beneficiary_id' => $branchDetails->branch_id,'status' => ITEM_ACTIVE])->count();
                    if($usageCount >= $voucher->limit_of_use) {                        
                        $error = __('apimsg.This voucher usage limit has finished');
                        goto error;
                    }
                }                    
                goto calculate;
            break;
            case VOUCHER_APPLY_PROMO_USERS:                
                /** if($voucher->promo_for_shops == PROMO_USER_ALL) No need to check all condition */
                if($voucher->promo_for_shops == PROMO_USER_PARTICULAR) {
                    $shopIsAvailable = VoucherBeneficiary::select('beneficiary_id')->where(['beneficiary_type' => VOUCHER_APPLY_PROMO_USERS,'voucher_id'=>$voucher->voucher_id,'beneficiary_id' => $userDetails->user_id])->count();
                    if( (int)$shopIsAvailable === 0) {                        
                        $error = __('apimsg.Voucher is unavailable for this shop');
                        goto error;
                    }                    
                }
                if($voucher->limit_of_use != 0) {
                    $usageCount = VoucherUsage::select('*')->where(['beneficiary_type' => VOUCHER_APPLY_PROMO_USERS,'voucher_id'=>$voucher->voucher_id,'beneficiary_id' => $userDetails->user_id,'status' => ITEM_ACTIVE])->count();
                    if($usageCount >= $voucher->limit_of_use) {     
                        $error = __('apimsg.This voucher usage limit has finished');
                        goto error;
                    }
                }
                goto calculate;
            break;
        }
        
        calculate:

        if ($itemSubtotal < $voucher->min_order_value) {            
            $error = "Voucher is valid only if you purchase for $voucher->min_order_value or more";
            goto error;
        }
        if($voucher->discount_type == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
            $discountAmount = ($itemSubtotal * $voucher->value) / 100;
        } else if($voucher->discount_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
            $discountAmount = $voucher->value;
        }
        if($discountAmount > $voucher->max_redeem_amount) {
            $discountAmount = $voucher->max_redeem_amount;
        }
        
        response:
        return ['status' => true, 'data' => $discountAmount];

        error:
        $this->errorFor('voucher_code');
        $response['error'] = $error;
        return $response;        
    }

    /**
     * @param array $reponseData 
     * @param boolean $isOrder
     */
    public function dataFormat($responseData, $isOrder = false)
    {
        /** Payment details */                
        if($isOrder === false) {
                        
            if(request()->corporate_voucher === null) {
                unset($responseData['total']['cprice']);
            }

            if(isset($responseData['payment_details'])) {
                foreach($responseData['payment_details'] as $key => $value) {
                    unset($responseData['payment_details'][$key]['cprice']);
                    unset($responseData['payment_details'][$key]['percent']);
                    unset($responseData['payment_details'][$key]['key']);
                    unset($responseData['payment_details'][$key]['delivery_distance']);
                }
            }            

            foreach($responseData['items'] as $key => $value) {
                unset($responseData['items'][$key]['cprice']);
                unset($responseData['items'][$key]['csubtotal']);
                foreach($value['ingredient_groups'] as $igKey => $igValue) {
                    unset($responseData['items'][$key]['ingredient_groups'][$igKey]['ingredient_group_csubtotal']);
                    if( isset($igValue['ingredients']) ){
                        foreach($igValue['ingredients'] as $iKey => $iValue) {
                            unset($responseData['items'][$key]['ingredient_groups'][$igKey]['ingredients'][$iKey]['ingredient_csubtotal']);
                            unset($responseData['items'][$key]['ingredient_groups'][$igKey]['ingredients'][$iKey]['cprice']);
                        }
                    }
                }
            }
            
            if(request()->corporate_voucher === null) {                                
                unset($responseData['sub_total']);
            }
            unset($responseData['delivery_cost']);
            unset($responseData['vat_tax']);
            unset($responseData['service_tax']);                             

        }else {
            unset($responseData['payment_details']);
        }                
        return $responseData;
    }    
    

    public function reOrder() 
    {
        DB::beginTransaction();
        try {
            $order = Order::findByKey(request()->order_key);                    
            if($order === null || $order->cart_id === null) {
                return $this->commonError(__("apimsg.Order Items not found"));
            } 
            
            /* Insert into cart */
            Cart::where([
                'user_id' => request()->user()->user_id,
                'branch_id' => $order->branch_id,
            ])->delete();
            $newCart = new Cart();
            $newCart = $newCart->fill([
                'user_id' => request()->user()->user_id,
                'branch_id' => $order->branch_id,
            ]);
            $newCart->save();
            $newCartID = $newCart->getKey();
            $oldcart = CartItem::where('cart_id',$order->cart_id)->get();
        
            foreach($oldcart as $oldCartKey => $oldCarValue) {
                
                $item = Item::find($oldCarValue->item_id);
                $fillableData = [];
                if($item === null || $item->status === ITEM_INACTIVE) {
                    return $this->commonError(__("apimsg.Item is unavailable"));
                    continue;
                }
                $fillableData = [
                    'cart_id' => $newCartID,
                    'item_id' => $oldCarValue->item_id,
                    'quantity' => $oldCarValue->quantity,
                    'is_ingredient' => $oldCarValue->is_ingredient,
                    'ingredients' => [],
                ];
                $ingredientGroups = [];
                $ingredientsGroupValues = json_decode($oldCarValue->ingredients,true);
                if(empty($ingredientsGroupValues) || $ingredientsGroupValues == null || count($ingredientsGroupValues) == 0) {
                    goto itemSave;
                }
                foreach($ingredientsGroupValues as $ingredientGroupKey => $ingredientGroupValue) {
                    $ingredientGroup = IngredientGroup::findByKey($ingredientGroupValue['ingredient_group_key']);
                    if($ingredientGroup === null || $ingredientGroup->status === ITEM_INACTIVE) {
                        continue;
                    }
                    $groupCount = ItemGroupMapping::where([
                        'item_id' => $oldCarValue->item_id,
                        'ingredient_group_id' => $ingredientGroup->ingredient_group_id
                    ])->count();
                    if($groupCount == 0) {
                        continue;
                    }
                    $groups['ingredient_group_key'] = $ingredientGroup->ingredient_group_key;
                    $groups['ingredients'] = [];                    
                    foreach($ingredientGroupValue['ingredients'] as $value) {
                        
                        $ingredient = Ingredient::findByKey($value['ingredient_key']);
                        
                        if($ingredient === null || $ingredient->status == ITEM_INACTIVE) {
                            continue;
                        }

                        $ingredientCount = IngredientGroupMapping::where([
                            'ingredient_group_id' => $ingredientGroup->ingredient_group_id,
                            'ingredient_id' => $ingredient->ingredient_id
                        ])->count();
                        if($ingredientCount == 0) {
                            continue;
                        }

                        $groups['ingredients'][] = ['ingredient_key' => $value['ingredient_key'], 'quantity' => 1 ];
                        
                    }                    
                    array_push($ingredientGroups,$groups);
                }
                itemSave:
                $fillableData['ingredients'] = json_encode($ingredientGroups);
                $oldcartItem = new CartItem();
                $oldcartItem = $oldcartItem->fill($fillableData);
                $oldcartItem->save();
            }
            DB::commit();
            $this->setMessage(__("apimsg.Same items has been added to your cart."));
            return $this->asJson();            
        } catch(Exception $e) {
            DB::rollback();
            throw $e->getMessage();
        }
    }


    public function saveOrderOnDeliveryBoy($orderKey)
    {   
        $orderDetails = Order::findByKey($orderKey);  
        
        if($orderDetails === null) { 
            return $this->commonError(__("apimsg.Order not found"));
        }
        if($orderDetails->order_type !== ORDER_TYPE_DELIVERY) {
            return $this->commonError(__("apimsg.Order not found"));
        }
        $vendor = Branch::select([
            Vendor::tableName().'.vendor_key',
            Vendor::tableName().'.vendor_id',
            Vendor::tableName().'.payment_option',
            Vendor::tableName().'.username as vendor_username',
            Vendor::tableName().'.email as vendor_email',
            Vendor::tableName().'.mobile_number as vendor_mobile_number',
            Vendor::tableName().'.contact_number as vendor_contact_number',            
                        
            Vendor::tableName().'.country_id as vendor_country_id',
            Vendor::tableName().'.city_id as vendor_city_id',
            Vendor::tableName().'.area_id as vendor_area_id',                        
            Vendor::tableName().'.latitude as vendor_latitude',
            Vendor::tableName().'.longitude as vendor_longitude',
            Vendor::tableName().'.tax as vendor_tax',
            Vendor::tableName().'.service_tax as vendor_service_tax',
            Vendor::tableName().'.commission as vendor_commission',
            Vendor::tableName().'.approved_status as vendor_approved_status',
            Vendor::tableName().'.status as vendor_status',

            Branch::tableName().'.branch_key',
            Branch::tableName().'.branch_id',
            Branch::tableName().'.order_type',
            Branch::tableName().'.contact_email as branch_contact_email',
            Branch::tableName().'.contact_number as branch_contact_number',
            Branch::tableName().'.restaurant_type as branch_restaurant_type',
            Branch::tableName().'.preparation_time as branch_preparation_time',
            Branch::tableName().'.delivery_time as branch_delivery_time',
            Branch::tableName().'.pickup_time as branch_pickup_time',
            Branch::tableName().'.country_id as branch_country_id',
            Branch::tableName().'.city_id as branch_city_id',
            Branch::tableName().'.area_id as branch_area_id',                        
            Branch::tableName().'.latitude as branch_latitude',
            Branch::tableName().'.longitude as branch_longitude',
        ])
        ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",'=',Vendor::tableName().'.vendor_id')
        ->where([
            Branch::tableName().'.status' => ITEM_ACTIVE,
            Vendor::tableName().'.status' => ITEM_ACTIVE,
            Branch::tableName().'.branch_id' => $orderDetails->branch_id,
            Vendor::tableName().'.vendor_id' => $orderDetails->vendor_id,
        ])->first();        
 
        $user = User::find($orderDetails->user_id);
        $userAddress = UserAddress::withTrashed()->find($orderDetails->user_address_id);
        $deliveryboyData = [ 
            'order_number' => $orderDetails->order_number,
            'order_key' => $orderKey,
            'vendor_key' => $vendor->vendor_key,
            'zipcode' => '',
            'payment_mode' => $orderDetails->payment_type,
            'amount' => $orderDetails->order_total,
            'order_time' => $orderDetails->order_datetime, 
            'delivery_time' => $orderDetails->delivery_datetime,
            'pickup_time' => $orderDetails->delivery_datetime,
            'delivery_type'  => $orderDetails->delivery_type,
            'delivery_fee' => $orderDetails->delivery_fee,
            'order_status' => NODE_ORDER_ACCEPTED,
            'customer_email' => $user->email,
            'order_assign_type' => config('webconfig.order_assign_type'),
            'vendor_details' => [],
            'order_details' => [],
            'pickup_location' => [],
            'delivery_location' => [],
            'location' => [
                'latitude' => $userAddress->latitude,
                'longitude' => $userAddress->longitude
            ],
            /* 'location' => [
                'latitude' => $vendor->vendor_latitude,
                'longitude' => $vendor->vendor_longitude
            ], */
            'vendor_location' => [                
                'latitude' => $vendor->vendor_latitude,
                'longitude' => $vendor->vendor_longitude                
            ],
        ];
        $languages = Common::getLanguages();
        foreach($languages as $key => $value) {

            $address = ''; //$userAddress->address_line_one.($userAddress->address_line_two === null)? "" : ", ".$userAddress->address_line_two.($userAddress->landmark ===  null) ? '' : ",".$userAddress->landmark
            if($userAddress->address_line_one !== null) {
                $address.= $userAddress->address_line_one;
            }
            if($userAddress->address_line_two !== null) {
                $address.= ", ".$userAddress->address_line_two;
            }
            if($userAddress->landmark !== null) {
                $address.= ", ".$userAddress->landmark;
            }
            if($userAddress->company !== null) {
                $address.= ", ".$userAddress->company;
            }
            $itemDetails = [
                'lang' => $key,
                'details' => [
                    'customer_name' => $user->first_name." ".$user->last_name,
                    'customer_mobile' => $user->phone_number,
                    'address' =>  $address,
                    'city' => '',
                    'country' => '',
                    'landmark' => ($userAddress->landmark ===  null) ? '' : $userAddress->landmark,
                    'bulk_items' => '',
                    'customer_image' => '',
                    'items' => [],
                ]
            ];
            $orderItem = OrderItem::leftJoin(OrderItemLang::tableName(),OrderItem::tableName().".order_item_id",OrderItemLang::tableName().".order_item_id")
                ->where([
                    OrderItemLang::tableName().".language_code" => $key,
                    OrderItem::tableName().".order_id" => $orderDetails->order_id,
                ])->get();
            foreach($orderItem as $itemKey => $itemvalue) {
                $itemDetails['details']['items'][$itemKey] = [
                    'name' => $itemvalue->item_name,
                    'price' => $itemvalue->base_price,
                    'quantity' => $itemvalue->item_quantity,
                    'ingredients' => [],
                ];
                $ingredients = OrderIngredient::leftJoin(OrderIngredientLang::tableName(), OrderIngredient::tableName().".order_ingredient_id",OrderIngredientLang::tableName().".order_ingredient_id")
                ->where([
                    OrderIngredientLang::tableName().".language_code" => $key,
                    OrderIngredient::tableName().".order_item_id" => $itemvalue->order_item_id,
                    OrderIngredient::tableName().".order_id" => $orderDetails->order_id
                ])->get();
                foreach($ingredients as $ingredientValue) {
                    $itemDetails['details']['items'][$itemKey]['ingredients'][] = [
                        'name' => $ingredientValue->ingredient_name,
                        'price' => $ingredientValue->ingredient_subtotal,
                        'quantity' => $ingredientValue->ingredient_quanitity,
                        'type' => 1, /*1-add , 2- remove*/                        
                        'cart_enable_size' => 0,
                        'cart_size' => 0,
                    ];
                }
            }                        
            array_push($deliveryboyData['order_details'],$itemDetails);
        }               

        /**
         * Vendor Details
         */            
        /* $vendorLang = VendorLang::where('vendor_id',$vendor->vendor_id)->get();                
        foreach($vendorLang as $key => $value) {
            $country = CountryLang::where(['language_code' => $value->language_code, 'country_id' => $vendor->vendor_country_id])->first();
            $countryName = ($country === null) ? '' : $country->country_name;

            $city = CityLang::where(['language_code' => $value->language_code, 'city_id' => $vendor->vendor_city_id])->first();
            $cityName = ($city === null) ? '' : $city->city_name;

            $area = AreaLang::where(['language_code' => $value->language_code, 'area_id' => $vendor->vendor_area_id])->first();
            $areaName = ($area === null) ? '' : $area->area_name;
            $details = [
                'lang' => $value->language_code,
                'details' => [
                    'vendor_address' => "$value->vendor_address, $areaName, $cityName, $countryName",
                    'vendor_area' => $areaName,
                    'vendor_city' => $cityName,
                    'vendor_country' => $countryName,
                    'vendor_mobile' => ($value->vendor_contact_number === null) ? '' : $value->vendor_contact_number,
                    'vendor_name' => ($vendor->vendor_name === null) ? '' : $vendor->vendor_name,
                ],
            ];
            array_push($deliveryboyData['vendor_details'],$details);
        } */

        
        /**
         * Pickup Loaction Details
         */
        $deliveryboyData['pickup_location'] = [
            'location' => [
                'latitude' => $vendor->branch_latitude,
                'longitude' => $vendor->branch_longitude
            ],
            'details' => []
        ];
        $branch = BranchLang::where('branch_id',$vendor->branch_id)->get();
        foreach($branch as $key => $value) {

            $country = CountryLang::where(['language_code' => $value->language_code, 'country_id' => $vendor->branch_country_id])->first();
            $countryName = ($country === null) ? '' : $country->country_name;

            $city = CityLang::where(['language_code' => $value->language_code, 'city_id' => $vendor->branch_city_id])->first();
            $cityName = ($city === null) ? '' : $city->city_name;

            $area = AreaLang::where(['language_code' => $value->language_code, 'area_id' => $vendor->branch_area_id])->first();
            $areaName = ($area === null) ? '' : $area->area_name;
            $details = [
                'lang' => $value->language_code,
                'details' => [
                    'address' => $value->branch_address,
                    'apartment' => '',
                    'area' => $areaName,
                    'city' => $cityName,
                    'company' => '',
                    'country' => $countryName,
                    'flat_no' => '',
                    'landmark' => '',
                    'street_name' => '',
                ],
            ];

            /** In vednor detail branch details are sending */
            $vendorDetails = [
                'lang' => $value->language_code,
                'details' => [
                    'vendor_address' => $value->branch_address,
                    'vendor_area' => $areaName,
                    'vendor_city' => $cityName,
                    'vendor_country' => $countryName,
                    'vendor_mobile' => ($value->contact_number === null) ? '' : $value->contact_number,
                    'vendor_name' => ($vendor->branch_name === null) ? '' : $vendor->branch_name,
                ],
            ];
            array_push($deliveryboyData['pickup_location']['details'],$details);
            array_push($deliveryboyData['vendor_details'],$vendorDetails);
        }
        
        /**
         * Delivery Location Details
         */
        $deliveryboyData['delivery_location'] = [
            'location' => [
                'latitude' => $userAddress->latitude,
                'longitude' => $userAddress->longitude
            ],
            'details' => []
        ];
        $languages = Common::getLanguages();
        foreach($languages as $key => $value) {
            $address = implode(", ", [ ($userAddress->address_line_one === null) ? '' : $userAddress->address_line_one , ($userAddress->address_line_two === null) ? '' : $userAddress->address_line_two ]);
            $details = [
                'lang' => $key,
                'details' => [
                    'address' => ($address === null) ? '' : $address,
                    'apartment' => '',
                    'area' => '',
                    'city' => '',
                    'company' => ($userAddress->company === null) ? '' : $userAddress->company,
                    'country' => '',
                    'flat_no' => '',
                    'landmark' => ($userAddress->landmark === null) ? '' : $userAddress->landmark,
                    'street_name' => '',
                ],
            ];
            array_push($deliveryboyData['delivery_location']['details'],$details);
        }
        $url = config('webconfig.deliveryboy_url')."/api/v1/order/create?company_id=".config('webconfig.company_id');        
        $postData = json_encode($deliveryboyData);   
        $data = Curl::instance()->action('POST')->setUrl($url)->setContentType('text/plain')->send($postData);
        $response = json_decode($data,true);
        if($response['status'] == HTTP_SUCCESS) {
            $order = Order::findByKey($orderKey);
            $order->order_refkey = isset($response['data']['order_id']) ? $response['data']['order_id'] : '';
            $order->save();
            return $this->asJson($response['message']);            
        } else {
            return $this->commonError($response['message']);                            
        }
    } 

    /** Node team status update */
    public function orderStatusUpdate()
    {   
        $orderModel = Order::findByKey(request()->order_key);
        if($orderModel === null) {
            return $this->commonError(__("apimsg.Order status is not found"));
        }
        /* $vendorDetails = Vendor::find($orderModel->vendor_id); */
        $userDetails = User::find($orderModel->user_id);
        $orderModel->deliveryboy_key = request()->deliveryboy_key;
        switch (request()->order_status) {
            case NODE_ORDER_PENDING:
                $orderModel->order_status = ORDER_APPROVED_STATUS_PENDING;
            break;
            case NODE_ORDER_ACCEPTED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_APPROVED;
            break;
            case NODE_ORDER_PREPARED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_PREPARING;
            break;
            case NODE_ORDER_ONTHEWAY:
                $orderModel->order_status = ORDER_ONTHEWAY;
                $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$orderModel->order_number." has been picked by driver and its on the way."], [$userDetails->device_token], []);
            break; 
            case NODE_ORDER_DELIVERED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_DELIVERED;
                Order::addLoyaltyPoints($orderModel);
                /* $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'Order Status'], ['en' => "Your order #".config('webconfig.app_inv_prefix').$orderStatus->order_number." has been delivered."], [$vendorDetails->device_token], []); */
                $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$orderModel->order_number." has been delivered successful."], [$userDetails->device_token], []);
            break;
            case NODE_ORDER_REJECTED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_REJECTED;
            break; 
            case NODE_ORDER_DRIVER_ASSIGNED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER;
            break;
            case NODE_ORDER_DRIVER_ACCEPTED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_DRIVER_ACCEPTED;
            break;
            case NODE_ORDER_DRIVER_REJECTED:
                $orderModel->order_status = ORDER_DRIVER_REJECTED;
            break;
            case NODE_ORDER_DRIVER_DELIVERED:
                $orderModel->order_status = ORDER_APPROVED_STATUS_DELIVERED;
                Order::addLoyaltyPoints($orderModel);
                /* $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'Order Status'], ['en' => "Your order #".config('webconfig.app_inv_prefix').$orderStatus->order_number." has been delivered."], [$vendorDetails->device_token], []); */
                $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$orderModel->order_number." has been delivered successful."], [$userDetails->device_token], []);
            break;
            case NODE_ORDER_DRIVER_REQUESTED:
                $orderModel->order_status = ORDER_DRIVER_REQUESTED;
            break;
            case NODE_ORDER_READY_TO_PICKUP:
                $orderModel->order_status = ORDER_APPROVED_STATUS_READY_FOR_PICKUP;                
            break;
        }

        $orderModel->save();
        $this->setMessage(__("apimsg.Order status updated successfully"));
        return $this->asJson();
         
    }    

}

