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
    CorporateVoucher,
    CorporateVoucherItem,
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


class CorporateOrderController extends Controller
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
        $corporateOrder = USER_TYPE_CORPORATES;
        $model = Order::getOrders($corporateOrder);
        if($request->ajax()) {
            $model = Order::getOrders($corporateOrder);
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name', function ($model) {
                                return ($model->first_name.$model->last_name);
                            })
                        ->editColumn('payment_type', function ($model) {
                                return $model->corporatePaymentTypes($model->payment_type);
                            })

                        /* ->editColumn('payment_status', function ($model) {
                                return $model->paymentStatus($model->payment_status);
                            }) */
                        ->editColumn('order_status', function ($model) {
                            
                                $status = $model->corporateOrderStatus($model->order_status);
                                return Form::select('order_status',$status, $model->order_status ,['class' => 'selectpicker order_status','route' => 'corporate-order.approvedstatus', 'id' => $model->{$model::uniqueKey()} ] );
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'corporate-order.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-order.show',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-order.edit',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'corporate-order.destroy',
                                    [ 'id' => $model->order_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$view$delete";
                            })
                        ->rawColumns(['status','order_approval_status', 'action'])
                        ->toJson();
        }
        $model = new Order;
        return view('admin.corporate-order.index',compact('model'));
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
        return view('admin.corporate-order.show', compact('model'));
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
        Common::log("Destroy","Corporate order has been deleted",new Order());
        return redirect()->route('corporate-order.index')->with('success', __('admincrud.Corporate order deleted successfully') );
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
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Corporate order status updated successfully') ];
            }            
            Common::log("Order Status","Corporate order status has been updated",$model);
            return response()->json($response);
        }
    }

    /**
     * Change the approved status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function corporateApprovedStatus(Request $request)
    {       
        if($request->ajax()) {

            $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Corporate order status updated successfully') ];            
            $model = Order::findByKey($request->order_key);
            $model->order_status = $request->order_status; 
            //if($request->order_status == ORDER_APPROVED_STATUS_DELIVERED || $request->order_status == ORDER_APPROVED_STATUS_PENDING) {
                $isEnabled = Order::select('order.order_id')->leftJoin('corporate_voucher','order.order_id','=','corporate_voucher.order_id')
                ->leftJoin('corporate_voucher_item','corporate_voucher.corporate_voucher_id','=','corporate_voucher_item.corporate_voucher_id')
                ->where([
                    'order.order_id' => $model->order_id,
                    'corporate_voucher_item.is_claimed' => 1,
                ])->first();
                if($isEnabled !== null) {
                    if($request->order_status == ORDER_APPROVED_STATUS_PENDING) {
                        $response = ['status' => AJAX_FAIL, 'msg' => __('admincrud.Already Voucher Completed') ];
                        goto response;
                    }
                }                
            //}
            $model->save();
            if($model) {
                if($model->order_status == ORDER_APPROVED_STATUS_DELIVERED) {
                    $orderItem = (new APIOrderController())->sendConfirmationMail($request->order_key);     
                    //$orderItem = Common::compressData($response);
                }
            }
            response:
            return response()->json($response); 
        }                            
    }
}