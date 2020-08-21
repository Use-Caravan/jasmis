<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\{
    Controllers\Api\V1\Controller,    
    Resources\Api\V1\UserResource
};
use App\{
    Api\User,
    Api\Transaction,
    Api\UserLoyaltyCredit,
    Api\LoyaltyLevel,
    Helpers\SadadPaymentGateway,
    Helpers\CredimaxPaymentGateway,
    PaymentGateway
};
use Auth;
use Common;
use DB;
use Validator;


class WalletController extends Controller
{
    public function addMoney()
    {
        $validator = Validator::make(request()->all(),[
            'amount' => 'required|numeric',
            //'transaction_type' => 'required|numeric',// 1 => Credit, 2 => Debit 
        ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        if(request()->amount < 0) {
            return $this->commonError( __("apimsg.Negative amount not accepted") );
        }
        DB::beginTransaction();
        try {
            $userID = Auth::user()->user_id;            

            /*$response = SadadPaymentGateway::instance()
                ->setAmount(request()->amount)
                ->setCustomerName(Auth::user()->first_name)
                ->setCustomerMail(Auth::user()->email)
                ->setCustomerPhone(Auth::user()->phone_number);
                if(request()->is_web !== null) {
                    $response = $response->setRequestFrom(request()->is_web);
                }
                $response = $response->makePayment();*/

            /** Add payment gateway details in PaymentGateway table and get id to send as order_id to credimax payment gateway **/
            $paymentData = [
                'customer_name' => Auth::user()->first_name,
                'customer_email' => Auth::user()->email,
                'customer_phone_number' => Auth::user()->phone_number,
                'price' => request()->amount,
            ];

            $paymentGateway = new PaymentGateway();                    
            $paymentGateway = $paymentGateway->fill([
                'sent_data' => json_encode($paymentData)
            ]);
            $paymentGateway->save();                    
            $payment_gateway_id = $paymentGateway->getKey();

            $temp_order_id = "";
            /** If payment process handled in mobile app **/
            if( isset( request()->transaction_type ) && request()->transaction_type > 0 )
            {
                $transactionData = [
                    'payment_gateway_id' => $payment_gateway_id,//$paymentGateway->getKey(),
                    'user_id' => Auth::user()->user_id,
                    'transaction_for' => TRANSACTION_FOR_ADD_TO_WALLET,
                    'transaction_type' => request()->transaction_type,
                    'amount' => request()->amount,
                    //'transaction_number' => $response['transaction-reference'],
                    //'transaction_number' => $response['PaymentID'],
                    'status' => TRANSACTION_STATUS_PENDING
                ];
                $transaction = new Transaction();
                $transaction = $transaction->fill($transactionData);
                $transaction->save();
                DB::commit();                    
                //print_r($transactionData);exit;
                $data = [
                    'temp_order_id' => $payment_gateway_id,
                    'amount' => request()->amount,
                    'payment_url' => "",
                    'transaction_reference' => ""
                ];   //print_r($data);exit;                 
                $this->setMessage( __("apimsg.Payment invoice is generated. Make payment by online") );
                return $this->asJson($data);
            }
            else
            {
                $response = CredimaxPaymentGateway::instance()
                                    ->setAmount(request()->amount)
                                    ->setCustomerId($userID)
                                    ->setOrderId($payment_gateway_id);
                if(request()->is_web !== null) {
                    $response = $response->setRequestFrom(request()->is_web);
                }
                $response = $response->makeWalletPayment();
                //print_r($response);exit;
                if($response !== null) {
                    $payment_gateway = PaymentGateway::find($payment_gateway_id);
                    $payment_gateway->gateway_url = $response['PaymentURL']."PaymentID=".$response['PaymentID'];
                    $payment_gateway->received_data = json_encode($response);
                    $payment_gateway->save();  

                    $transactionData = [
                        'payment_gateway_id' => $payment_gateway_id,//$paymentGateway->getKey(),
                        'user_id' => Auth::user()->user_id,
                        'transaction_for' => TRANSACTION_FOR_ADD_TO_WALLET,
                        'transaction_type' => TRANSACTION_TYPE_CREDIT,
                        'amount' => request()->amount,
                        //'transaction_number' => $response['transaction-reference'],
                        'transaction_number' => $response['PaymentID'],
                        'status' => TRANSACTION_STATUS_PENDING
                    ];
                    $transaction = new Transaction();
                    $transaction = $transaction->fill($transactionData);
                    $transaction->save();
                    DB::commit();                    
                    //print_r($transactionData);exit;
                    $data = [
                        'temp_order_id' => $payment_gateway_id,
                        'payment_url' => $response['PaymentURL']."PaymentID=".$response['PaymentID'],
                        'transaction_reference' => $response['PaymentID'],
                        'amount' => request()->amount
                    ];   //print_r($data);exit;                 
                    $this->setMessage( __("apimsg.Payment invoice is generated. Make payment by online") );
                    //print_r($this->asJson($data));exit;
                    return $this->asJson($data);

                    /* $user = User::find($userID);
                    $user->wallet_amount = ( (double)$user->wallet_amount + request()->amount);
                    $user->save(); */
                }
            }            
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }        
    }

    public function redeemPoint()
    {
        $validator = Validator::make(request()->all(),[
            'points' => 'required|numeric',
        ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        DB::beginTransaction();
        try {
            $userID = Auth::user()->user_id;
            $user = User::find($userID);
            if($user->loyalty_points < request()->points) {
                return $this->commonError(__("apimsg.Your requesting loyalty point is too large"));
            }

            $loyaltyLevel = LoyaltyLevel::where('from_point', '<=', $user->loyalty_points)->where('to_point', '>=', $user->loyalty_points)->orderBy('loyalty_level_id','ASC')->first();            

            if($loyaltyLevel === null) {
                return $this->commonError(__("apimsg.You dont have Loyalty level"));
            }

            $amountForOnePoint = ($loyaltyLevel->redeem_amount_per_point === null) ? 0 : $loyaltyLevel->redeem_amount_per_point;
            $requestPoint = request()->points;
            $amount = $requestPoint * $amountForOnePoint;
            $transaction = new Transaction();
            $transaction = $transaction->fill([
                'user_id' => $userID,
                'transaction_for' => POINTS_REDEEM_TRANSACTION,
                'transaction_type' => TRANSACTION_TYPE_CREDIT,
                'transaction_number' => Common::generateRandomString(Transaction::tableName(), 'transaction_number', $length = 32),
                'amount' => $amount,
                'status' => 2,
            ]);
            $transaction->save();
                            
            // $userLoyaltyCredit = new UserLoyaltyCredit();
            // $userLoyaltyCredit = $userLoyaltyCredit->fill([
            //     'user_id' => $userID,
            //     'loyalty_point' => request()->points,
            //     'transaction_for' => 2,
            //     'previous_user_point' => $user->loyalty_points,
            //     'current_user_point' => ( (int)$user->loyalty_points - request()->points),
            // ]);
            // $userLoyaltyCredit->save();
            
            $user->wallet_amount = ( (double)$user->wallet_amount + $amount);
            $user->loyalty_points = ( (int)$user->loyalty_points - request()->points);
            $user->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        $this->setMessage(__("apimsg.Points are converted as money"));
        $this->setData(new UserResource($user));
        return $this->asJson();
    }
}
