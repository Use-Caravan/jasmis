<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\{
    Order,
    Deliveryboy,
    Branch,
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

class ReportController extends Controller
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
     */
    public function index(Request $request)
    {   
        $modelOrder = new Order();
        $model = Order::getReports()->paginate(100); 
        $branchList = Branch::getBranch($request->vendor_id);
        $vendorList = Vendor::getVendors();
         switch(APP_GUARD) {
            case GUARD_ADMIN:
                $branchList = [];
                if($request->vendor_id !== null) {
                    $branchList = Branch::getBranch($request->vendor_id);
                }                                
                break;
            case GUARD_VENDOR:
                $branchList = Branch::getBranch(Auth()->guard(APP_GUARD)->user()->vendor_id);
                break;
            case GUARD_OUTLET:
                $branchList = [];
                break;
        }
        return view('admin.report.index',compact('model','branchList','vendorList','modelOrder'));
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
        $model = Order::getOrderDetails($id);
        return view('admin.report.show', compact('model'));
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
     */
    public function destroy($id)
    {   
        $model = Order::findByKey($id)->delete();
        Common::log("Destroy","Report has been deleted",new Order());
        return redirect()->route('report.index')->with('success', __('admincrud.Report deleted successfully') );
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
            $model = Report::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Order status updated successfully') ];
            }            
            Common::log("Order Status","Order status has been updated",$model);
            return response()->json($response);
        }
    }

    public function reportExport()
    {
        Common::log("Report Export","Reports exported as excel file",new Order());
        //$model = Order::getOrders()->get();
        return $this->excel->download(new OrderExport, 'report-'.date('y-m-d').'.xlsx');
    }
}
