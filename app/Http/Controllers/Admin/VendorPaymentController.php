<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\
{
    Branch,
    Order,
    Vendor
};
use Common;
use DataTables;
use HtmlRender;
use Html;
use DatePeriod;
use DateTime;
use DateInterval;

class VendorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {                   
        $dates = Vendor::vendorPaymentTimeslot();
        $vendorPayments = Order::vendorPaymentDetails();
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
        
        $model = new Order();
        return view('admin.vendorpayment.index',compact('vendorList','branchList','vendorPayments','dates'));
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
        //
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
     * @Title("Delete")
     */
    public function destroy($id)
    {
        $vendorpayment = Order::findByKey($id);        
        $vendorpayment = $vendorpayment->delete();
        Common::log("Destroy","Vendorpayment has been deleted",new Order());
        return redirect()->route('vendorpayment.index')->with('success', __('admincrud.Payment Details deleted successfully') );
    }
   
}
