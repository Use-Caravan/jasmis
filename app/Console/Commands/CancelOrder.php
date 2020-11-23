<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Order;
use App\User;
use App\Helpers\Curl;
use App\Helpers\OneSignal;
use App\Helpers\FireBase;

class CancelOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel the order after the second cut off time limit if no drivers accepts the order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orders = Order::getOrders()
                    ->whereIn(Order::tableName().".order_status",[
                        ORDER_APPROVED_STATUS_PENDING,
                        ORDER_DRIVER_REQUESTED,
                        ORDER_DRIVER_REJECTED,
                        ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER
                    ]);
        $orders = $orders->get();
        $status = [
            ORDER_DRIVER_REQUESTED,
            ORDER_DRIVER_REJECTED,
            ORDER_APPROVED_STATUS_PENDING,
            ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER
        ];

        foreach( $orders as $model )
        {
            if($model->order_type == ORDER_TYPE_DELIVERY) {
                if(in_array($model->order_status, $status)) {
                    $current_time = date('Y-m-d H:i:s');
                    /** Change order status to rejected and refund to customer if order second cut off time limit exceed **/
                    if( isset( $model->first_cut_off_time ) && isset( $model->second_cut_off_time ) && ( strtotime( $current_time ) > strtotime( $model->first_cut_off_time ) ) && ( strtotime( $current_time ) > strtotime( $model->second_cut_off_time ) ) )
                    {
                        $order_key = $model->order_key;
                        $user_id = $model->user_id;
                        $item_total = $model->item_total;
                        $payment_type = $model->payment_type;
                        if( ( $user_id > 0 ) && !empty( $order_key ) && ( $item_total > 0 ) )
                        {
                            $user = User::find($user_id);

                            /** Refund to customer while cancel order if payment type is online / cpocket / online & cpocket **/
                            if($payment_type == PAYMENT_OPTION_ONLINE || $payment_type == PAYMENT_OPTION_WALLET || $payment_type == PAYMENT_OPTION_WALLET_AND_ONLINE){
                                //$user = User::find($user_id);
                                if( $user )
                                {
                                    $user->wallet_amount = ( (double)$user->wallet_amount + $item_total);
                                    $user->save();
                                }
                            }

                            /** Send push notification to customer if order second cut off time limit exceed **/ 
                            $order_datetime = $model->order_datetime;
                            $order_date = date( "dmY", strtotime( $order_datetime ) );
                            $order_time = date( "Hi", strtotime( $order_datetime ) );
                            //$oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "We regret to inform you that your order #".config('webconfig.app_inv_prefix').$model->order_number." placed on ".$order_date." at ".$order_time." has been cancelled. If you have paid for the order, it'll be added to your cPocket wallet and you can use it for future orders."], [$user->device_token], []);

                            /** Send push notification to customer app from firebase **/
                            $fireBaseCustomer  = FireBase::getInstance()->setAppType(FIRE_BASE_USER_APP)->push('Orders', 'Order Status', "We regret to inform you that your order #".config('webconfig.app_inv_prefix').$model->order_number." placed on ".$order_date." at ".$order_time." has been cancelled. If you have paid for the order, it'll be added to your cPocket wallet and you can use it for future orders.", $user->device_token, [], "No", $user->device_type);
                            /** Send push notification to customer if order second cut off time limit exceed **/

                            /** Change order status to rejected **/
                            $model = Order::findByKey($order_key);
                            if( $model ){
                                $model->order_status = ORDER_APPROVED_STATUS_REJECTED;
                                if($model->save()){  
                                    $orderStatus = (new Order)->convertWebtoDeliveryboystatus(ORDER_APPROVED_STATUS_REJECTED);
                                    $url = config('webconfig.deliveryboy_url')."/api/v1/order/$order_key/$orderStatus?company_id=".config('webconfig.company_id')."&from_driver=0";
                                    $data = Curl::instance()->action("PUT")->setUrl($url)->send([]);
                                    if($data === false) {
                                        $this->info('Server not started');
                                    }
                                    else
                                    {
                                        $response = json_decode($data,true);
                                        if($response['status'] != HTTP_SUCCESS) {
                                            $this->info('Error in cancel order');
                                        } 
                                        else        
                                            $this->info($response['status'].'Order cancelled successfully - '.$model->order_number);
                                    }
                                }
                                else
                                    $this->info('Error in cancel order - '.$model->order_number);
                            }
                        }
                    }
                    else
                        $this->info('No orders found to cancel');
                }
            }
        }
    }
}
