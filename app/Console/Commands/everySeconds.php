<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\{
    User,
    Order
};

class everySeconds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order update';

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
        $orders = Order::where('order_status',ORDER_APPROVED_STATUS_APPROVED)->where('order_type',ORDER_TYPE_DELIVERY)->get();

        foreach ($orders as $order) {
            $current_time = date('Y-m-d H:i:s');
            $expired_time = date('Y-m-d H:i:s',strtotime('+6 minutes',strtotime($order->order_datetime)));
            if($expired_time < $current_time){

                if($order->payment_type == PAYMENT_OPTION_ONLINE || $order->payment_type == PAYMENT_OPTION_WALLET || $order->payment_type == PAYMENT_OPTION_WALLET_AND_ONLINE){
                    $user = User::find($order->user_id);
                    $user->wallet_amount = ( (double)$user->wallet_amount + $order->item_total);
                    $user->save();
                }
                $model = Order::findByKey($order->order_key);
                $model->order_status = ORDER_APPROVED_STATUS_REJECTED;
                if($model->save()){
                    echo "Order status updated successfully";
                }
               
            }

        }
    }
}
