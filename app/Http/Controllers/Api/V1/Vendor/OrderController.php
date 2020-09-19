<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Api\V1\Controller;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController as APIOrderController;
use App\Http\Resources\Api\V1\Vendor\OrderResource;
use App\Http\Resources\Api\V1\CalculateResource;
use App\Mail\OrderConfirmation;
use App\Helpers\Curl;
use App\Api\{
    Cart,
    CartItem,
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
    Order,
    OrderItem,
    OrderItemLang,
    OrderItemIngredientGroup,
    OrderItemIngredientGroupLang,
    OrderIngredient,
    ItemGroupMapping,
    OrderIngredientLang
};
use Auth;
use Common;
use DB;
use FileHelper;
use Mail;
use Schema;
use Storage;
use Validator;
use App\Vendor as CommonVendor;
use App\Helpers\OneSignal;
//use App\Helpers\Curl;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function incomingOrders()
    {    
        $user = request()->user();         
        $orders = Order::getIncomingOrders();
        $orders = OrderResource::collection($orders->get());
        $this->setMessage(__('apimsg.Orders are fetched'));
        return $this->asJson($orders);    
    } 

    public function acceptedOrders()
    {        
        $orders = Order::getAcceptedOrders();
        $orders = OrderResource::collection($orders->get());
        $this->setMessage(__('apimsg.Orders are fetched'));
        return $this->asJson($orders);    
    } 
    
    
    public function showOrder($order_key) 
    {   
        $orders = Order::getVendorShowOrders();
        $orders = $orders->where(Order::tableName().".order_key",$order_key);
        $orders = new OrderResource($orders->first());
        $this->setMessage(__('apimsg.Orders are fetched'));
        return $this->asJson($orders);    
    }

    public function changeStatus($order_key)
    {   
        
        $order = Order::findByKey($order_key);  
        

        if($order === null) {
            return $this->commonError(__("apimsg.Order is not found"));
        }
 
        $userDetails = User::find($order->user_id);
        $vendorDetails = Vendor::find($order->vendor_id);        

        if(request()->user() instanceof CommonVendor) {
            if($order->vendor_id != request()->user()->vendor_id) {
                return $this->commonError(__("apimsg.You don't have access to change status for this order"));    
            }
        } else {
            if($order->branch_id != request()->user()->branch_id) {
                return $this->commonError(__("apimsg.You don't have access to change status for this order"));
            }
        }
        
        $availableChangeStatus = [
            ORDER_APPROVED_STATUS_APPROVED,
            ORDER_APPROVED_STATUS_REJECTED,
            ORDER_APPROVED_STATUS_PREPARING,
            ORDER_APPROVED_STATUS_READY_FOR_PICKUP,
            
        ];
        if($order->order_type == ORDER_TYPE_PICKUP_DINEIN) {
            array_push($availableChangeStatus, ORDER_APPROVED_STATUS_DELIVERED);
        }

        //if($order->order_type == ORDER_TYPE_DELIVERY && ( request()->order_status == ORDER_APPROVED_STATUS_PREPARING || request()->order_status == ORDER_APPROVED_STATUS_APPROVED ) ) {
        if($order->order_type == ORDER_TYPE_DELIVERY && ( request()->order_status == ORDER_APPROVED_STATUS_APPROVED ) ) {
            if($order->order_status !== ORDER_APPROVED_STATUS_DRIVER_ACCEPTED) {
                return $this->commonError(__("apimsg.Order not accepted by the driver"));
            }            
        }

        if(!in_array(request()->order_status, $availableChangeStatus)) {
            return $this->commonError(__("apimsg.You don't have access to change status for this order"));
        }
        
        if($order->order_status == request()->order_status) {
            return $this->commonError(__("apimsg.Order status already updated"));
        }    
            
        if(request()->order_status == ORDER_APPROVED_STATUS_APPROVED) {
                
            $order->order_approved_datetime = date('Y-m-d H:i:s');
            $order->order_rejected_datetime = null;
            /*if($order->order_type === ORDER_TYPE_DELIVERY) {
                $url = config('webconfig.deliveryboy_url')."/api/v1/driver/company?company_id=".config('webconfig.company_id');
                $data = Curl::instance()->setUrl($url)->send();
                $response = json_decode($data,true);
                $driverslist = $response['data'];
                //print_r($driverslist);
                $saveOrderOnDeliveryBoy = (new APIOrderController)->saveOrderOnDeliveryBoy($order_key);
                $response = Common::compressData($saveOrderOnDeliveryBoy);    
                if($response->status != HTTP_SUCCESS) {
                    return $this->commonError($response->message);
                }
                $assign_driver_count = 0;
                foreach($driverslist as $key => $value) {
                    $deliveryboy_key = $value['_id'];
                    $url = config('webconfig.deliveryboy_url')."/api/v1/order/$order_key/assign_driver/$deliveryboy_key?company_id=".config('webconfig.company_id');
                    $data = Curl::instance()->action(METHOD_PUT)->setUrl($url)->send();        
                    $response_assign = json_decode($data,true);
                    //print_r($response_assign);

                    if( isset( $response_assign['status'] ) && $response_assign['status'] === HTTP_SUCCESS)
                        $assign_driver_count++;          
                }//echo "assign_driver_count = ".$assign_driver_count;
            }*/
            $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$order->order_number." is accepted by restaurant."], [$userDetails->device_token], []);
        }
        
        if(request()->order_status == ORDER_APPROVED_STATUS_REJECTED) {
            
            // refund to customer 
            if($order->payment_type == PAYMENT_OPTION_ONLINE || $order->payment_type == PAYMENT_OPTION_WALLET || $order->payment_type == PAYMENT_OPTION_WALLET_AND_ONLINE){
                $user = User::find($order->user_id);
                $user->wallet_amount = ( (double)$user->wallet_amount + $order->item_total);
                $user->save();
            }
            
            $order->order_rejected_datetime = date('Y-m-d H:i:s');
            $order->order_approved_datetime = null;
            $order->order_reject_reason = request()->order_reject_reason;

        }
        if(request()->order_status == ORDER_APPROVED_STATUS_READY_FOR_PICKUP) {
            if($order->order_type == ORDER_TYPE_DELIVERY) {
                //$oneSignalVendor  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_VENDOR_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$order->order_number." is ready to pickup."], [$vendorDetails->device_token], []);

                $deliveryboy_key = $order->deliveryboy_key;
                $url_push = config('webconfig.deliveryboy_url')."/api/v1/driver/$deliveryboy_key?company_id=".config('webconfig.company_id');
                $response_push = new Curl();
                $response_push->setUrl($url_push);        
                $data_push = $response_push->send();
                $response_push = json_decode($data_push,true);
                //print_r($response_push);exit;

                if( isset( $response_push['data'] ) ) {
                    $deviceTokenRider = ( isset( $response_push['data']['device_token'] ) ) ? $response_push['data']['device_token'] : "";

                    if( !empty( $deviceTokenRider ) ) {
                        $oneSignalRider  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_DRIVER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$order->order_number." is ready to pickup."], [$deviceTokenRider], []);
                        //print_r($oneSignalRider);exit;
                    }
                }
            }

            $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$order->order_number." is ready to pickup."], [$userDetails->device_token], []);
        }

        if(request()->order_status == ORDER_APPROVED_STATUS_DELIVERED) {
            Order::addLoyaltyPoints($order);
            $oneSignalCustomer  = OneSignal::getInstance()->setAppType(ONE_SIGNAL_USER_APP)->push(['en' => 'Order Status'], ['en' => "Order #".config('webconfig.app_inv_prefix').$order->order_number." has been delivered successfully."], [$userDetails->device_token], []);
        }

        $order->order_status = request()->order_status;        
        
        if(request()->order_status != ORDER_APPROVED_STATUS_APPROVED && $order->order_type === ORDER_TYPE_DELIVERY) {
            $orderStatus = (new Order)->convertWebtoDeliveryboystatus(request()->order_status);        
            $vendorLocation = Order::getVendorLocation($order_key);
            $vendorDetailsforDeliveryboy['vendor_location'] = [
                    'latitude' => $vendorLocation['latitude'],
                    'longitude' => $vendorLocation['longitude']
                ];
                    
            $vendorLocationJson = json_encode($vendorDetailsforDeliveryboy);                         
            
            $url = config('webconfig.deliveryboy_url')."/api/v1/order/$order_key/$orderStatus?company_id=".config('webconfig.company_id');
            $data = Curl::instance()->action("PUT")->setUrl($url)->send($vendorLocationJson);                        
            $response = json_decode($data,true);            
            if($response['status'] == HTTP_SUCCESS) {                
               goto success;
            } else {
                return $this->commonError($response['message']);
            }
        }
        success:
        $order->save();
        $this->setMessage(__("apimsg.Order status updated successfully"));
        return $this->asJson();        
    }


    public function report()
    {
        $orders = Order::getReport();        
        $orders = OrderResource::collection($orders->get());
        $this->setMessage(__('apimsg.Orders are fetched'));
        return $this->asJson($orders);    
    }
}
