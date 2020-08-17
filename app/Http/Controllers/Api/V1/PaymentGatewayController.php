<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\CartController;
use App\ {
    Transaction,
    PaymentGateway,
    VoucherUsage,
    Api\Order,
    Api\User,
    Api\Vendor,
    Api\UserLoyaltyCredit,
    Api\LoyaltyPoint,
    Api\Cart
};
use App\CorporateVoucher;
use App\CorporateVoucherItem;
//use App\Helpers\OneSignal;
use App\Helpers\{
    OneSignal,
    Curl,
    CredimaxPaymentGateway
};
use Auth;

class PaymentGatewayController extends Controller
{
    public function success(Request $request) 
    {   
        $orderID = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($order_id);
            $response = $response->getPaymentDetails();
        }
                    
        if($request->TransactionIdentifier != null && $request->TransactionIdentifier != '') {
            $transaction = Transaction::where('transaction_number',$request->TransactionIdentifier)->first();
            if($transaction !== null) {
                $transactionID = $transaction->transaction_id;
                $transactionNumber = $transaction->transaction_number;
                $transaction->status = TRANSACTION_STATUS_SUCCESS;
                $transaction->save();
                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                $paymentGateway->response_received_data = json_encode($request->all());
                $paymentGateway->status = ORDER_PAYMENT_STATUS_SUCCESS;
                $paymentGateway->save();                                

                switch($transaction->transaction_for) {
  
                    case TRANSACTION_FOR_ONLINE_BOOKING:

                        $order = Order::where('transaction_id',$transactionID)->first();

                        if($order->claim_corporate_offer_booking === 1) {
                            $corporateOffer = CorporateVoucher::where(['order_id' => $order->order_id ])->first();
                            if($corporateOffer !== null) {
                                $corporateVoucherItem = CorporateVoucherItem::where(['corporate_voucher_id' => $corporateOffer->corporate_voucher_id])->first();
                                $corporateVoucherItem->is_claimed = 1;
                                $corporateVoucherItem->claimed_at = date('Y-m-d H:i:s');
                                $corporateVoucherItem->save();
                            }
                        }

                        $userDetails = User::find($order->user_id);
                        $userDetails->wallet_amount = 0;
                        $userDetails->save();
                        $vendorDetails = Vendor::find($order->vendor_id);
                        $branch_id = $order->branch_id;
                        $orderkey = $order->order_key;
                        $order->payment_status = ORDER_PAYMENT_STATUS_SUCCESS;
                        $order->save();                        
                        
                        
                       
                        /**
                         * Clear Cart after payment
                         */
                        $cart = Cart::where(['user_id' => $userDetails->user_id,'branch_id' => $branch_id])->delete();


                        $voucherUsageStatus = VoucherUsage::where(['order_id' => $order->order_id])->first();
                        if($voucherUsageStatus !== null) {
                            $voucherUsageStatus->status = ITEM_ACTIVE;
                            $voucherUsageStatus->save(); 
                        }  
                       


                        /**
                         * Send Notification after payment success
                         */
                        $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Notification'], ['en' => 'Order placed successfully.'], [$userDetails->device_token], []);
                        $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->device_token], []);                           
                        if($vendorDetails->web_app_id !== null) {
                            $oneSignalVendorWeb  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_WEB_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->web_app_id], []);
                        } 
                        
                       
                        if($request->is_web == true || $request->is_web == 1) {                            
                            return redirect()->route('frontend.confirmation',['order_key' => $orderkey]);
                        } else {                                                                                    
                            
                            $this->setMessage(__("apimsg.Payment has been success."));
                            $data = [
                                'order_key' => $orderkey
                            ];
                            return $this->asJson($data);
                        }
                        break;
                    case TRANSACTION_FOR_ADD_TO_WALLET:
                    
                        $user = User::find($transaction->user_id);
                        $user->wallet_amount = ( (double)$user->wallet_amount + $transaction->amount);
                        $user->save();                        
                        if($request->is_web == true || $request->is_web == 1) { 
                                                       
                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                        } else {                            
                            $this->setMessage(__("apimsg.Payment has been success."));
                            return $this->asJson([]);
                        }
                        break;
                }
            } else {
                return $this->commonError(__("apimsg.Transaction is not found"));    
            }
        } else {
            return $this->commonError(__("apimsg.Payment process is not working currently"));
        }
    }

    public function failiur(Request $request) 
    {
        if($request->TransactionIdentifier != null && $request->TransactionIdentifier != '') {
            $transaction = Transaction::where('transaction_number',$request->TransactionIdentifier)->first();
            if($transaction !== null) {
                $transactionNumber = $transaction->transaction_number;
                $transactionID = $transaction->transaction_id;
                $transaction->status = TRANSACTION_STATUS_FAILED;
                $transaction->save();
                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                $paymentGateway->response_received_data = json_encode($request->all());
                $paymentGateway->status = ORDER_PAYMENT_STATUS_FAILURE;
                $paymentGateway->save();

                switch($transaction->transaction_for) {

                    case TRANSACTION_FOR_ONLINE_BOOKING:

                        $order = Order::where('transaction_id',$transactionID)->first();
                        $orderkey = $order->order_key;
                        $order->payment_status = ORDER_PAYMENT_STATUS_FAILURE;
                        $order->save();

                        if($request->is_web == true || $request->is_web == 1) {
                            return redirect()->route('frontend.failed',['order_key' => $orderkey]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                        break;
                    case TRANSACTION_FOR_ADD_TO_WALLET:
                        if($request->is_web == true || $request->is_web == 1) {

                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                        break;
                }                
            } else {
                return $this->commonError(__("apimsg.Transaction is not found")); 
            }            
        } else {
            return $this->commonError(__("apimsg.Payment process is not working currently"));
        }
    }

    /** Success redirect url from credimax **/
    /*public function credimaxSuccess(Request $request) 
    {   
        $orderID = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($orderID);
            $response = $response->getPaymentDetails();
            if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                if( $response["paymnet_requests"]["status"] == "SUCCESS" )
                {
                    if( $response["paymnet_requests"]["order_id"] == $orderID )
                    {
                        if( isset( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ) {
                            $transaction = Transaction::where('transaction_number', $response["paymnet_requests"]["payment_response"]["tnx_id"])->first();
                            if($transaction !== null) {
                                $transactionID = $transaction->transaction_id;
                                $transactionNumber = $transaction->transaction_number;
                                $transaction->status = TRANSACTION_STATUS_SUCCESS;
                                $transaction->save();
                                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                                $paymentGateway->response_received_data = json_encode($response);
                                $paymentGateway->status = ORDER_PAYMENT_STATUS_SUCCESS;
                                $paymentGateway->save();                                

                                switch($transaction->transaction_for) {                  
                                    case TRANSACTION_FOR_ONLINE_BOOKING:
                                        $order = Order::where('transaction_id',$transactionID)->first();
                                        if($order->claim_corporate_offer_booking === 1) {
                                            $corporateOffer = CorporateVoucher::where(['order_id' => $order->order_id ])->first();
                                            if($corporateOffer !== null) {
                                                $corporateVoucherItem = CorporateVoucherItem::where(['corporate_voucher_id' => $corporateOffer->corporate_voucher_id])->first();
                                                $corporateVoucherItem->is_claimed = 1;
                                                $corporateVoucherItem->claimed_at = date('Y-m-d H:i:s');
                                                $corporateVoucherItem->save();
                                            }
                                        }

                                        $userDetails = User::find($order->user_id);
                                        $userDetails->wallet_amount = 0;
                                        $userDetails->save();
                                        $vendorDetails = Vendor::find($order->vendor_id);
                                        $branch_id = $order->branch_id;
                                        $orderkey = $order->order_key;
                                        $order->payment_status = ORDER_PAYMENT_STATUS_SUCCESS;
                                        $order->save();
                       
                                        // Clear Cart after payment
                                        $cart = Cart::where(['user_id' => $userDetails->user_id,'branch_id' => $branch_id])->delete();

                                        $voucherUsageStatus = VoucherUsage::where(['order_id' => $order->order_id])->first();
                                        if($voucherUsageStatus !== null) {
                                            $voucherUsageStatus->status = ITEM_ACTIVE;
                                            $voucherUsageStatus->save(); 
                                        }

                                        // Send Notification after payment success
                                        $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Notification'], ['en' => 'Order placed successfully.'], [$userDetails->device_token], []);
                                        $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->device_token], []);                           
                                        if($vendorDetails->web_app_id !== null) {
                                            $oneSignalVendorWeb  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_WEB_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->web_app_id], []);
                                        }                                        
                                       
                                        if($request->is_web == true || $request->is_web == 1) {                            
                                            return redirect()->route('frontend.confirmation',['order_key' => $orderkey]);
                                        } else {
                                            $this->setMessage(__("apimsg.Payment has been success."));
                                            $data = [
                                                'order_key' => $orderkey
                                            ];
                                            return $this->asJson($data);
                                        }
                                    break;

                                    case TRANSACTION_FOR_ADD_TO_WALLET:                                    
                                        $user = User::find($transaction->user_id);
                                        $user->wallet_amount = ( (double)$user->wallet_amount + $transaction->amount);
                                        $user->save();                        
                                        if($request->is_web == true || $request->is_web == 1) { 
                                                                       
                                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                                        } else {                            
                                            $this->setMessage(__("apimsg.Payment has been success."));
                                            return $this->asJson([]);
                                        }
                                    break;
                                }
                            } else {
                                return $this->commonError(__("apimsg.Transaction is not found"));    
                            }
                        } else {
                            return $this->commonError(__("apimsg.Payment process is not working currently"));
                        }
                    }
                    else {
                        return $this->commonError(__("apimsg.Invalid Transaction"));    
                    }
                }
                else {
                    return $this->commonError(__("apimsg.Payment Failed"));    
                }
            }
            else {
                return $this->commonError(__("apimsg.Payment Failed"));    
            }
        }
        else {
            return $this->commonError(__("apimsg.Payment Failed"));    
        }        
    }*/

    /** Success redirect url from credimax for order payment with credit, debit cards **/
    public function credimaxSuccess(Request $request) 
    {   
        $orderID = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($orderID);
            $response = $response->getPaymentDetails();
            if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                if( $response["paymnet_requests"]["status"] == "SUCCESS" )
                {
                    if( $response["paymnet_requests"]["order_id"] == $orderID )
                    {
                        $payment_gateway_id = $orderID;
                        /*$payment_gateway = PaymentGateway::find($payment_gateway_id);
                        $payment_gateway->gateway_url = $response['PaymentURL']."PaymentID=".$response['PaymentID'];
                        $payment_gateway->received_data = json_encode($response);
                        $payment_gateway->save();*/

                        $user_id = ( $response["paymnet_requests"]["customer_id"] ) ? $response["paymnet_requests"]["customer_id"] : "";
                        $paidAmount = ( $response["paymnet_requests"]["amount"] ) ? $response["paymnet_requests"]["amount"] : ""; 
                        $transaction_number = ( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ? $response["paymnet_requests"]["payment_response"]["tnx_id"] : "";

                        $transaction_type = ( $response["paymnet_requests"]["payment_type"] ) ? $response["paymnet_requests"]["payment_type"] : TRANSACTION_TYPE_DEBIT;

                        $transactionData = [
                            'payment_gateway_id' => $payment_gateway_id,//$paymentGateway->getKey(),
                            'user_id' => $user_id,
                            'transaction_for' => TRANSACTION_FOR_ONLINE_BOOKING,
                            //'transaction_type' => TRANSACTION_TYPE_DEBIT,
                            'transaction_type' => $transaction_type,
                            'amount' => $paidAmount,
                            'transaction_number' => $transaction_number,
                            'status' => TRANSACTION_STATUS_SUCCESS
                        ];

                        $transaction = new Transaction();
                        $transaction = $transaction->fill($transactionData);
                        $transaction->save();
                        $transactionID = $transaction->getKey();

                        $paymentGateway = PaymentGateway::find($payment_gateway_id);
                        $paymentGateway->response_received_data = json_encode($response);
                        $paymentGateway->status = ORDER_PAYMENT_STATUS_SUCCESS;
                        $paymentGateway->save();                                

                        /*switch($transaction->transaction_for) {                  
                            case TRANSACTION_FOR_ONLINE_BOOKING:
                                $order = Order::where('transaction_id',$transactionID)->first();
                                if($order->claim_corporate_offer_booking === 1) {
                                    $corporateOffer = CorporateVoucher::where(['order_id' => $order->order_id ])->first();
                                    if($corporateOffer !== null) {
                                        $corporateVoucherItem = CorporateVoucherItem::where(['corporate_voucher_id' => $corporateOffer->corporate_voucher_id])->first();
                                        $corporateVoucherItem->is_claimed = 1;
                                        $corporateVoucherItem->claimed_at = date('Y-m-d H:i:s');
                                        $corporateVoucherItem->save();
                                    }
                                }

                                $userDetails = User::find($order->user_id);
                                $userDetails->wallet_amount = 0;
                                $userDetails->save();
                                $vendorDetails = Vendor::find($order->vendor_id);
                                $branch_id = $order->branch_id;
                                $orderkey = $order->order_key;
                                $order->payment_status = ORDER_PAYMENT_STATUS_SUCCESS;
                                $order->save();
               
                                // Clear Cart after payment
                                $cart = Cart::where(['user_id' => $userDetails->user_id,'branch_id' => $branch_id])->delete();

                                $voucherUsageStatus = VoucherUsage::where(['order_id' => $order->order_id])->first();
                                if($voucherUsageStatus !== null) {
                                    $voucherUsageStatus->status = ITEM_ACTIVE;
                                    $voucherUsageStatus->save(); 
                                }

                                // Send Notification after payment success
                                $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Notification'], ['en' => 'Order placed successfully.'], [$userDetails->device_token], []);
                                $oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->device_token], []);                           
                                if($vendorDetails->web_app_id !== null) {
                                    $oneSignalVendorWeb  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_WEB_APP)->push(['en' => 'New order'], ['en' => 'You have new incoming order.'], [$vendorDetails->web_app_id], []);
                                }                                        
                               
                                if($request->is_web == true || $request->is_web == 1) {                            
                                    return redirect()->route('frontend.confirmation',['order_key' => $orderkey]);
                                } else {
                                    $this->setMessage(__("apimsg.Payment has been success."));
                                    $data = [
                                        'order_key' => $orderkey
                                    ];
                                    return $this->asJson($data);
                                }
                            break;

                            case TRANSACTION_FOR_ADD_TO_WALLET:                                    
                                $user = User::find($transaction->user_id);
                                $user->wallet_amount = ( (double)$user->wallet_amount + $transaction->amount);
                                $user->save();                        
                                if($request->is_web == true || $request->is_web == 1) { 
                                                               
                                    return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                                } else {                            
                                    $this->setMessage(__("apimsg.Payment has been success."));
                                    return $this->asJson([]);
                                }
                            break;
                        }*/

                        $this->setMessage(__("apimsg.Payment has been success."));
                        $data = $response;
                        return $this->asJson($data);
                    }
                    else {
                        //return $this->commonError(__("apimsg.Invalid Transaction"));  
                        $this->setMessage(__("apimsg.Invalid Transaction"));
                        $data = $response;
                        return $this->asJson($data);  
                    }
                }
                else {
                    //return $this->commonError(__("apimsg.Payment Failed"));    
                    $this->setMessage(__("apimsg.Payment Failed"));
                    $data = $response;
                    return $this->asJson($data);
                }
            }
            else {
                //return $this->commonError(__("apimsg.Payment Failed")); 
                $this->setMessage(__("apimsg.Payment Failed"));
                $data = $response;
                return $this->asJson($data);   
            }
        }
        else {
            return $this->commonError(__("apimsg.Payment Failed"));
        }        
    }

    public function test() 
    {   
        echo 'test';exit;
    }

    /** Credimax failure redirect URL **/
    public function credimaxFailure( Request $request ) 
    {   
        $orderID = $order_id = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($order_id);
            $response = $response->getPaymentDetails();
            //print_r($response);exit;
            $transactionNumber = null;
            /*if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                if( $response["paymnet_requests"]["status"] != "SUCCESS" )
                {
                    if( $response["paymnet_requests"]["order_id"] == $orderID )
                    {
                        if( isset( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ) {
                            $transaction = Transaction::where('transaction_number', $response["paymnet_requests"]["payment_response"]["tnx_id"])->first();
                        }
                    }
                }
            }
            else
            {
                $order_det = Order::where('order_id', $orderID)->first();
                if($order_det !== null) {
                    $transaction_id = $order_det->transaction_id;
                    if( isset( $transaction_id ) && $transaction_id > 0 ) {
                        $transaction = Transaction::where('transaction_id', $transaction_id)->first();        
                    }
                }
            }*/   

            $payment_gateway_id = $orderID;
            $user_id = $paidAmount = $transaction_number = "";
            $transaction_type = TRANSACTION_TYPE_DEBIT;
            if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                //echo $payment_gateway_id;exit;
                $user_id = ( $response["paymnet_requests"]["customer_id"] ) ? $response["paymnet_requests"]["customer_id"] : "";
                //echo $user_id;exit;
                $paidAmount = ( $response["paymnet_requests"]["amount"] ) ? $response["paymnet_requests"]["amount"] : ""; 
                $transaction_number = ( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ? $response["paymnet_requests"]["payment_response"]["tnx_id"] : "";
                $transaction_type = ( $response["paymnet_requests"]["payment_type"] ) ? $response["paymnet_requests"]["payment_type"] : TRANSACTION_TYPE_DEBIT;
            }

            $transactionData = [
                'payment_gateway_id' => $payment_gateway_id,//$paymentGateway->getKey(),
                'user_id' => $user_id,
                'transaction_for' => TRANSACTION_FOR_ONLINE_BOOKING,
                //'transaction_type' => TRANSACTION_TYPE_DEBIT,
                'transaction_type' => $transaction_type,
                'amount' => $paidAmount,
                'transaction_number' => $transaction_number,
                'status' => TRANSACTION_STATUS_FAILED
            ];
            //print_r($transactionData);exit;

            $transaction = new Transaction();
            $transaction = $transaction->fill($transactionData);
            $transaction->save();
            $transactionID = $transaction->getKey();

            $paymentGateway = PaymentGateway::find($payment_gateway_id);
            if( $paymentGateway ) {
                $paymentGateway->response_received_data = json_encode($response);
                $paymentGateway->status = ORDER_PAYMENT_STATUS_FAILURE;
                $paymentGateway->save();                                
            }
            
            $this->setMessage(__("apimsg.Payment cannot capture"));
            $data = $response;
            return $this->asJson($data);

            /*if($transaction !== null) {
                $transactionNumber = $transaction->transaction_number;
                $transactionID = $transaction->transaction_id;
                $transaction->status = TRANSACTION_STATUS_FAILED;
                $transaction->save();
                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                $paymentGateway->response_received_data = json_encode($response);
                $paymentGateway->status = ORDER_PAYMENT_STATUS_FAILURE;
                $paymentGateway->save();

                switch($transaction->transaction_for) {
                    case TRANSACTION_FOR_ONLINE_BOOKING:
                        $order = Order::where('transaction_id',$transactionID)->first();
                        $orderkey = $order->order_key;
                        $order->payment_status = ORDER_PAYMENT_STATUS_FAILURE;
                        $order->save();

                        if($request->is_web == true || $request->is_web == 1) {
                            return redirect()->route('frontend.failed',['order_key' => $orderkey]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                    break;
                    case TRANSACTION_FOR_ADD_TO_WALLET:
                        if($request->is_web == true || $request->is_web == 1) {

                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                    break;
                }                
            } else {
                return $this->commonError(__("apimsg.Transaction is not found")); 
            }*/            
        } else {
            return $this->commonError(__("apimsg.Payment process is not working currently"));
        }
    }
    
    /** Success redirect url from credimax for add wallet **/
    public function credimaxWalletSuccess(Request $request) 
    {   
        $orderID = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($orderID);
            $response = $response->getPaymentDetails();
            if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                if( $response["paymnet_requests"]["status"] == "SUCCESS" )
                {
                    if( $response["paymnet_requests"]["order_id"] == $orderID )
                    {
                        if( isset( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ) {
                            $transaction = Transaction::where('transaction_number', $response["paymnet_requests"]["payment_response"]["tnx_id"])->first();
                            if($transaction !== null) {
                                $transactionID = $transaction->transaction_id;
                                $transactionNumber = $transaction->transaction_number;
                                $transaction->status = TRANSACTION_STATUS_SUCCESS;
                                $transaction->save();
                                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                                $paymentGateway->response_received_data = json_encode($response);
                                $paymentGateway->status = ORDER_PAYMENT_STATUS_SUCCESS;
                                $paymentGateway->save();                                

                                switch($transaction->transaction_for) {                  
                                    case TRANSACTION_FOR_ADD_TO_WALLET:                                    
                                        $user = User::find($transaction->user_id);
                                        $user->wallet_amount = ( (double)$user->wallet_amount + $transaction->amount);
                                        $user->save();                        
                                        if($request->is_web == true || $request->is_web == 1) {
                                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                                        } else {                            
                                            $this->setMessage(__("apimsg.Payment has been success."));
                                            return $this->asJson([]);
                                        }
                                    break;
                                }
                            } else {
                                return $this->commonError(__("apimsg.Transaction is not found"));    
                            }
                        } else {
                            return $this->commonError(__("apimsg.Payment process is not working currently"));
                        }
                    }
                    else {
                        return $this->commonError(__("apimsg.Invalid Transaction"));    
                    }
                }
                else {
                    return $this->commonError(__("apimsg.Payment Failed"));    
                }
            }
            else {
                return $this->commonError(__("apimsg.Payment Failed"));    
            }
        }
        else {
            return $this->commonError(__("apimsg.Payment Failed"));    
        }        
    }

    /** Credimax failure redirect URL for add wallet **/
    public function credimaxWalletFailure( Request $request ) 
    {   
        $orderID = $order_id = isset( $request->order_id ) ? $request->order_id : "";
        if( !empty( $orderID ) )
        {
            $response = CredimaxPaymentGateway::instance()->setOrderId($order_id);
            $response = $response->getPaymentDetails();
            $transactionNumber = null;
            if( $response["status"] == 1 && !empty( $response["paymnet_requests"] ) )
            {
                $response["paymnet_requests"] = $response["paymnet_requests"][0];
                if( $response["paymnet_requests"]["status"] != "SUCCESS" )
                {
                    if( $response["paymnet_requests"]["order_id"] == $orderID )
                    {
                        if( isset( $response["paymnet_requests"]["payment_response"]["tnx_id"] ) ) {
                            $transaction = Transaction::where('transaction_number', $response["paymnet_requests"]["payment_response"]["tnx_id"])->first();
                        }
                    }
                }
            }
            else
            {
                $transaction = Transaction::where('payment_gateway_id', $orderID)->first();
            }   
            if( isset( $transaction ) && $transaction !== null) {
                $transactionNumber = $transaction->transaction_number;
                $transactionID = $transaction->transaction_id;
                $transaction->status = TRANSACTION_STATUS_FAILED;
                $transaction->save();
                $paymentGateway = PaymentGateway::find($transaction->payment_gateway_id);
                $paymentGateway->response_received_data = json_encode($response);
                $paymentGateway->status = ORDER_PAYMENT_STATUS_FAILURE;
                $paymentGateway->save();

                switch($transaction->transaction_for) {
                    case TRANSACTION_FOR_ONLINE_BOOKING:
                        $order = Order::where('transaction_id',$transactionID)->first();
                        $orderkey = $order->order_key;
                        $order->payment_status = ORDER_PAYMENT_STATUS_FAILURE;
                        $order->save();

                        if($request->is_web == true || $request->is_web == 1) {
                            return redirect()->route('frontend.failed',['order_key' => $orderkey]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                    break;
                    case TRANSACTION_FOR_ADD_TO_WALLET:
                        if($request->is_web == true || $request->is_web == 1) {
                            return redirect()->route('frontend.wallet',['transaction_number' => $transactionNumber]);
                        } else {
                            return $this->commonError(__("apimsg.Payment cannot capture"));
                        }
                    break;
                }                
            } else {
                return $this->commonError(__("apimsg.Transaction is not found")); 
            }            
        } else {
            return $this->commonError(__("apimsg.Payment process is not working currently"));
        }
    }
}

