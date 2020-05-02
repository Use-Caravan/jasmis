<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Http\Controllers\Api\V1\OrderController as APIOrderController;
use App\{
    Order,
    Deliveryboy,
    UserAddress,
    Vendor,
    Exports\OrderExport 
};
use Common;
use Maatwebsite\Excel\Exporter;
use Maatwebsite\Excel\Excel;
use DataTables;
use App\Helpers\Curl;
use DB;
use FileHelper;
use Hash;
use HtmlRender;
use Html;
use Form;


class OrderController extends Controller
{
    
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title('List')
     */
    public function index(Request $request)
    {        
        if($request->ajax()) {
            $model = Order::getOrders();           
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name', function ($model) {
                                return ($model->first_name.$model->last_name);
                            })
                        ->editColumn('payment_type', function ($model) {
                                return $model->paymentTypes($model->payment_type);
                            })

                        ->editColumn('payment_status', function ($model) {
                                return $model->paymentStatus($model->payment_status);
                            })
                        ->editColumn('order_status', function ($model) {
                                $status = $model->orderStatus($model->order_status,$model->order_type);
                                return Form::select('order_status',$status, $model->order_status ,['class' => 'selectpicker order_status','route' => 'order.approvedstatus', 'id' => $model->{$model::uniqueKey()} ] );
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'order.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                $assignDelivery = "";
                                $status = [
                                    ORDER_DRIVER_REQUESTED,
                                    ORDER_DRIVER_REJECTED,
                                    ORDER_APPROVED_STATUS_APPROVED,
                                ];
                                if($model->order_type == ORDER_TYPE_DELIVERY) {
                                    if(in_array($model->order_status, $status)) {
                                        $assignDelivery = '<a href="javascript:" id="'.$model->order_key.'_assignOrder" orderKey="'.$model->order_key.'" class="assignOrder" title="Assign Order"><i class="fa fa-motorcycle"></i></a>';
                                    }
                                }
                                $status = [
                                    ORDER_APPROVED_STATUS_DRIVER_PICKED_UP,
                                    ORDER_ONTHEWAY,
                                ];
                                $trackOrder = '';
                                if(in_array($model->order_status, $status)) {
                                    $trackOrder = '<a href="'.route('order.track',['order_key' => $model->order_key]).'" id="'.$model->order_key.'_trackOrder" orderKey="'.$model->order_key.'" class="trackOrder" title="Assign Order"><i class="fa fa-map-marker"></i></a>';
                                }                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'order.show',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'order.edit',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'order.destroy',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$assignDelivery$view$delete$trackOrder";
                            })
                        ->rawColumns(['status','order_approval_status', 'action'])
                        ->toJson();
        }
        $model = new Order;
        return view('admin.order.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        // $model = Order::findByKey($id);
        $model = Order::getOrderDetails($id);
        return view('admin.order.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title('Delete')
     */
    public function destroy($id)
    {   
        $model = Order::findByKey($id)->delete();
        Common::log("Destroy","Order has been deleted",new Order());
        return redirect()->route('order.index')->with('success', __('admincrud.Order deleted successfully') );
    }

         /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Order::findByKey($request->itemkey);    
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Order status updated successfully') ];
            }            
            Common::log("Order Status","Order status has been updated",$model);
            return response()->json($response);
        }
    }

    /**
     * Change the approved status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function approvedStatus(Request $request)
    {   
        if($request->ajax()) {
            $model = Order::findByKey($request->order_key);
            $order_key = $request->order_key;
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            if($model->order_type === ORDER_TYPE_PICKUP_DINEIN) {
                goto changeStatus;
            }
            if($model->order_type === ORDER_TYPE_DELIVERY) {
                if($request->order_status == ORDER_APPROVED_STATUS_APPROVED) { 
                    goto deliveryBoyPlaceOrder;                        
                } else if($request->order_status == ORDER_APPROVED_STATUS_PENDING || $request->order_status == ORDER_APPROVED_STATUS_REJECTED || $request->order_status == ORDER_APPROVED_STATUS_DELIVERED)                    
                     goto changeStatus;                        
                } else {
                    goto deliveryboyChangeStatus;        
                }                
            }
            
            deliveryBoyPlaceOrder:
            $response = (new APIOrderController())->saveOrderOnDeliveryBoy($request->order_key);
            $deliveryboyResponse = Common::compressData($response);
            if($deliveryboyResponse->status == HTTP_SUCCESS) {
                goto changeStatus;
            } else {
                return response()->json($response);
            }


            deliveryboyChangeStatus:
            $orderStatus = (new Order)->convertWebtoDeliveryboystatus($request->order_status);
            $url = config('webconfig.deliveryboy_url')."/api/v1/order/$order_key/$orderStatus?company_id=".config('webconfig.company_id')."&from_driver=0";
            $data = Curl::instance()->action("PUT")->setUrl($url)->send([]);
            if($data === false) {
                $response['msg'] = 'Server not started';
                return response()->json($response);
            }
            $response = json_decode($data,true);
            if($response['status'] != HTTP_SUCCESS) {
                return response()->json($response);
            }

            Error:
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.You have not allowed') ];
            return response()->json($response);

            changeStatus:
            if($request->order_status == ORDER_APPROVED_STATUS_DELIVERED) {
                Order::addLoyaltyPoints($model);
            }
            $model = Order::findByKey($order_key);
            $model->order_status = $request->order_status;
            if($model->save()){
               
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Order approved status updated successfully') ];
            }
            Common::log("Approved Status Update","Order approved status has been changed",$model);
            return response()->json($response);

            
        
    }

    public function orderExport()
    {
        Common::log("Order Export","Orders exported as excel file",new Order());
        //$model = Order::getOrders()->get();
        return $this->excel->download(new orderExport, 'orders-'.date('y-m-d').'.xlsx');
    }   


    public function getAvailableDeliveryboys(Request $request)
    {   
        $url = config('webconfig.deliveryboy_url')."/api/v1/driver/company?company_id=".config('webconfig.company_id');
        $data = Curl::instance()->setUrl($url)->send();
        $response = json_decode($data,true);
        $deliveryboy = new Deliveryboy();
        $driverslist = $response['data'];
        $order_key = $request->order_key;
        $drivers = view('admin.order.__driverlist',compact('driverslist','deliveryboy','order_key'))->render();
        return response()->json(['status' => HTTP_SUCCESS,'data' => $drivers, 'msg' => __('admincrud.Order assign to driver')]);
    }

    public function assignDeliveryboy(Request $request)
    {        
        $order_key = $request->order_key;        
        $deliveryboy_key = $request->deliveryboy_key;
        $url = config('webconfig.deliveryboy_url')."/api/v1/order/$order_key/assign_driver/$deliveryboy_key?company_id=".config('webconfig.company_id');
        $data = Curl::instance()->action(METHOD_PUT)->setUrl($url)->send();        
        $response = json_decode($data,true);        
        
        if($response['status'] === HTTP_SUCCESS) {
            $order = Order::findByKey($order_key);            
            $order->order_status = ORDER_APPROVED_STATUS_ASSIGNED_TO_DRIVER;            
            $order->save();
            return response()->json(['status' => HTTP_SUCCESS,'message' => 'Order assigned to driver']);
        } else {
            return response()->json(['status' => HTTP_UNPROCESSABLE,'message' => $response['message']]);
        }        
    }

    public function trackOrder($order_key)
    {
        $order = Order::findByKey($order_key);
        $userAddress = UserAddress::find($order->user_address_id);
        return view('admin.order.track-order',compact('order_key','userAddress'));
    }
}