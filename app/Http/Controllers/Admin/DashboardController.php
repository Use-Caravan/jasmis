<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use App\Branch;
use App\Vendor;
use App\Helpers\Curl;

class DashboardController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Dashboard Controller
    |--------------------------------------------------------------------------
    |


    /**
     * Dashboard
     *
     * @return view
     */
    public function index()
    {          
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver/company?company_id=".config('webconfig.company_id');
        $response = new Curl();
        $response->setUrl($url);        
        $data = $response->send();
        $response = json_decode($data,true);
        $drivers = [];
        if($response['status'] === HTTP_SUCCESS) {
            $drivers = $response['data'];
        }

        
        $orderReport = [
            'total_orders' => Order::getOrdersCount(ORDER_COUNT_TYPE_ALL),
            'pending_orders' => Order::getOrdersCount(ORDER_COUNT_TYPE_PENDING),
            'delivered_orders' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED),
            'rejected_orders' => Order::getOrdersCount(ORDER_COUNT_TYPE_REJECTED),
            'user_count' => User::count(),
            'branch_count' => Branch::count(),
            'driver_count' => count($drivers),
            'total_turnover' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_ALL,false),
            
            'today' => [
                'order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_ALL,ORDER_COUNT_DAY_TYPE_TODAY),
                'order_total' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_TODAY,false),
                'pending_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_PENDING,ORDER_COUNT_DAY_TYPE_TODAY),
                'delivered_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_TODAY),
                'rejected_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_REJECTED,ORDER_COUNT_DAY_TYPE_TODAY),
            ],
            'month' => [
                'order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_ALL,ORDER_COUNT_DAY_TYPE_MONTH),
                'order_total' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_MONTH,false),
                'pending_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_PENDING,ORDER_COUNT_DAY_TYPE_MONTH),
                'delivered_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_MONTH),
                'rejected_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_REJECTED,ORDER_COUNT_DAY_TYPE_MONTH),
            ],
            'year' => [
                'order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_ALL,ORDER_COUNT_DAY_TYPE_YEAR),
                'order_total' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_YEAR,false),
                'pending_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_PENDING,ORDER_COUNT_DAY_TYPE_YEAR),
                'delivered_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_YEAR),
                'rejected_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_REJECTED,ORDER_COUNT_DAY_TYPE_YEAR),
            ],
            'quater_year' => [
                'order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_ALL,ORDER_COUNT_DAY_TYPE_QUATER_YEAR),
                'order_total' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_QUATER_YEAR,false),
                'pending_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_PENDING,ORDER_COUNT_DAY_TYPE_QUATER_YEAR),
                'delivered_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_DELIVERED,ORDER_COUNT_DAY_TYPE_QUATER_YEAR),
                'rejected_order_count' => Order::getOrdersCount(ORDER_COUNT_TYPE_REJECTED,ORDER_COUNT_DAY_TYPE_QUATER_YEAR),
            ],
        ];   
        return view('admin.dashboard.index',['order_report' => $orderReport]);
    }

    public function webPushNotificationRegister(Request $request)
    {     
        if($request->ajax()) {
            $vendorAppId = Vendor::find($request->user_id);
            $vendorAppId->web_app_id = $request->appId;
            $vendorAppId->save();
            return response()->json(['status' => HTTP_SUCCESS, 'data' => $vendorAppId]);
        }
    }
}
