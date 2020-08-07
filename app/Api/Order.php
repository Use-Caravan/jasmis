<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;
use App\Order as CommonOrder;
use App\Vendor as CommonVendor;
use App\BranchUser as CommonBranchUser;
use App\UserAddress as CommonUserAddress;
use DB;

class Order extends CommonOrder
{
    public static function getVendorOrders()
    {
        $orders = Order::select([
            Order::tableName().".*",
            DB::raw("CONCAT(first_name,' ',last_name) as customer_name"),
        ])
        ->leftJoin(Vendor::tableName(),Order::tableName().".vendor_id",'=',Vendor::tableName().".vendor_id")
        ->leftJoin(User::tableName(),Order::tableName().".user_id",'=',User::tableName().".user_id")->whereNotNull(User::tableName().".user_id");

        $orders = $orders->where([
            Order::tableName().'.status' => ITEM_ACTIVE,
        ])->orderBy('order_id','desc');

        
        $orders = $orders->where(function($orders) {
            $orders->where([
                Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
            ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD);
        });

        return Self::scopeUser($orders);
    }    
    
    public static function getVendorShowOrders()
    {
        $orders = Order::select([
            Order::tableName().".*",
            DB::raw("CONCAT(first_name,' ',last_name) as customer_name"),
        ])
        ->leftJoin(Vendor::tableName(),Order::tableName().".vendor_id",'=',Vendor::tableName().".vendor_id")
        ->leftJoin(User::tableName(),Order::tableName().".user_id",'=',User::tableName().".user_id");

        $orders = $orders->where([
            Order::tableName().'.status' => ITEM_ACTIVE,
        ])->orderBy('order_id','desc');

        
        $orders = $orders->where(function($orders) {
            $orders->where([
                Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
            ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD);
        });
        //print_r($orders->get());exit;
        return Self::scopeUser($orders);
    }    

    public static function getIncomingOrders()
    {
        $orders = Self::getVendorOrders();
        //return $orders->where(Order::tableName().".order_status",ORDER_APPROVED_STATUS_PENDING);  
        /** For new order flow changes we have to show pending, driver requested, driver accepted orders also in vendor incoming orders list **/    
        return $orders->whereIn(Order::tableName().".order_status",[
                ORDER_APPROVED_STATUS_PENDING,
                ORDER_DRIVER_REQUESTED,
                ORDER_APPROVED_STATUS_DRIVER_ACCEPTED,
                ORDER_DRIVER_REJECTED
            ]);                
    }

    public static function getAcceptedOrders()
    {
        $orders = Self::getVendorOrders();
        /*return $orders->whereIn(Order::tableName().".order_status",[
                ORDER_APPROVED_STATUS_APPROVED,
                ORDER_APPROVED_STATUS_PREPARING,
                ORDER_APPROVED_STATUS_DRIVER_ACCEPTED,
                ORDER_APPROVED_STATUS_READY_FOR_PICKUP,
                ORDER_APPROVED_STATUS_DRIVER_PICKED_UP,
            ]);*/
        /** For new order flow changes no need to show driver accepted orders in vendor accepted orders list **/
        return $orders->whereIn(Order::tableName().".order_status",[
                ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER,
                ORDER_APPROVED_STATUS_APPROVED,
                ORDER_APPROVED_STATUS_PREPARING,
                ORDER_APPROVED_STATUS_READY_FOR_PICKUP,
                ORDER_APPROVED_STATUS_DRIVER_PICKED_UP,
            ]);
    }

    public static function getReport()
    {
        $orders = Self::getVendorOrders();
        
        if(request()->from_date !== null && request()->to_date !== null) {
            $orders = $orders->whereDate('order_datetime','>=',request()->from_date)->whereDate('order_datetime','<=',request()->to_date);
        }
        $orderStatus = [
            ORDER_APPROVED_STATUS_APPROVED,
            ORDER_APPROVED_STATUS_REJECTED,
            ORDER_APPROVED_STATUS_DELIVERED,
            ORDER_APPROVED_STATUS_PENDING,
        ];
        if(request()->order_status !== null && in_array(request()->order_status, $orderStatus)) {
            $orders = $orders->where(Order::tableName().".order_status",request()->order_status);
        }
        
        return $orders;
    }

    public static function scopeUser($orders)
    {
        if(request()->user() instanceof CommonVendor) {
            return $orders->where(Order::tableName().".vendor_id",request()->user()->vendor_id);
        } else {
            return $orders->where(Order::tableName().".branch_id",request()->user()->branch_id);
        }
    }

    public static function getVendorLocation($orderKey)
    {  
        $vendorLocation = Order::select([Vendor::tableName().'.latitude',Vendor::tableName().'.longitude'])
                          ->leftjoin(Vendor::tableName(),Order::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
                          ->where([Order::tableName().'.order_key' => $orderKey])->first();
        return $vendorLocation;
    }


    public static function getCustomerOrders()
    {           
        $order = Order::where('order_key',request()->order_key)->first();
        $orders = Order::select(Order::tableName().".*",
            Branch::tableName().".branch_key",
            Vendor::tableName().".color_code",
            Branch::tableName().".latitude as branch_latitude",
            Branch::tableName().".longitude as branch_longitude",
            UserAddress::tableName().".latitude as user_latitude",
            UserAddress::tableName().".longitude as user_longitude"
        )
        ->leftJoin(Branch::tableName(),Order::tableName().".branch_id",'=',Branch::tableName().".branch_id")
        ->leftJoin(Vendor::tableName(),Branch::tableName().".vendor_id",'=',Vendor::tableName().".vendor_id")
        ->leftJoin(UserAddress::tableName(),Order::tableName().".user_address_id",'=',UserAddress::tableName().".user_address_id");
        
        if(request()->order_key !== null && $order !== null && (int)$order->order_booked_by === USER_TYPE_CUSTOMER) {
            $orders = $orders->where(Order::tableName().'.user_id',request()->user()->user_id)->orderBy(Order::tableName().'.order_id','desc');
        }
        
        BranchLang::selectTranslation($orders);
      
        $orders = $orders->where([
            Order::tableName().'.status' => ITEM_ACTIVE,
        ])->orderBy('order_id','desc');
        
        if(request()->order_key !== null) {
            $orders = $orders->where(Order::tableName().".order_key",request()->order_key);
        }
        
        if(request()->order_key !== null && $order !== null && (int)$order->order_booked_by === USER_TYPE_CUSTOMER) {
            $orders = $orders->where(function($orders) {
                $orders->where([
                    Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                    [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
                ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD);
            });        
        }
        
        /*$orders = $orders->where(function($orders) {
            $orders->where('payment_type', '<>', PAYMENT_OPTION_ONLINE)
                ->orWhere(function ($query) {
                    $query->where('payment_type', '=', PAYMENT_OPTION_ONLINE)
                          ->where('payment_status', '=', ORDER_PAYMENT_STATUS_SUCCESS);
                });
        });*/
        
        /** Show payment success orders only for payment type online, wallet **/
        $orders = $orders->where(function($orders) {
        $orders->where([
                Order::tableName().'.payment_status' => ORDER_PAYMENT_STATUS_SUCCESS,
                [Order::tableName().'.payment_type', '<>', PAYMENT_OPTION_COD]
            ])->orWhere(Order::tableName().'.payment_type', PAYMENT_OPTION_COD);
        });

        $orders = $orders->groupBy('order_id');
        return $orders;
    }

}
