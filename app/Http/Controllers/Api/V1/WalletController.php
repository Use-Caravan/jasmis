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

            $response = SadadPaymentGateway::instance()
                ->setAmount(request()->amount)
                ->setCustomerName(Auth::user()->first_name)
                ->setCustomerMail(Auth::user()->email)
                ->setCustomerPhone(Auth::user()->phone_number);
                if(request()->is_web !== null) {
                    $response = $response->setRequestFrom(request()->is_web);
                }
                $response = $response->makePayment();
                if($response !== null) {

                    $paymentData = [
                        'customer_name' => Auth::user()->first_name,
                        'customer_email' => Auth::user()->email,
                        'customer_phone_number' => Auth::user()->phone_number,
                        'price' => request()->amount,
                    ];

                    $paymentGateway = new PaymentGateway();                    
                    $paymentGateway = $paymentGateway->fill([
                        'sent_data' => json_encode($paymentData),
                        'gateway_url' => $response['payment-url'],
                        'received_data' => json_encode($response)
                    ]);
                    $paymentGateway->save();                    
                    $transactionData = [
                        'payment_gateway_id' => $paymentGateway->getKey(),
                        'user_id' => Auth::user()->user_id,
                        'transaction_for' => TRANSACTION_FOR_ADD_TO_WALLET,
                        'transaction_type' => TRANSACTION_TYPE_CREDIT,
                        'amount' => request()->amount,
                        'transaction_number' => $response['transaction-reference'],
                        'status' => TRANSACTION_STATUS_PENDING
                    ];
                    $transaction = new Transaction();
                    $transaction = $transaction->fill($transactionData);
                    $transaction->save();
                    DB::commit();                    

                    $data = [
                        'payment_url' => $response['payment-url'],
                        'transaction_reference' => $response['transaction-reference']
                    ];                    
                    $this->setMessage( __("apimsg.Payment invoice is generated. Make payment by online") );
                    return $this->asJson($data);

                    /* $user = User::find($userID);
                    $user->wallet_amount = ( (double)$user->wallet_amount + request()->amount);
                    $user->save(); */
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
                            
            $userLoyaltyCredit = new UserLoyaltyCredit();
            $userLoyaltyCredit = $userLoyaltyCredit->fill([
                'user_id' => $userID,
                'loyalty_point' => request()->points,
                'transaction_for' => 2,
                'previous_user_point' => $user->loyalty_points,
                'current_user_point' => ( (int)$user->loyalty_points - request()->points),
            ]);
            $userLoyaltyCredit->save();
            
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
