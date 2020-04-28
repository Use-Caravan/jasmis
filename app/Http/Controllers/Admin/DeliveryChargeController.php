<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\DeliveryChargeRequest
};

use App\{
    DeliveryCharge
};

use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;


/**
 * @Title("Delivery Charge Management") 
 */
class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $model = DeliveryCharge::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'deliverycharge.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'deliverycharge.show',
                                    [ 'id' => $model->delivery_charge_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'deliverycharge.edit',
                                    [ 'id' => $model->delivery_charge_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'deliverycharge.destroy',
                                    [ 'id' => $model->delivery_charge_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.deliverycharge.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new DeliveryCharge();
        if($request->old()) {
            $model = $model->fill($request->old());
        }       
        return view('admin.deliverycharge.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryChargeRequest $request)
    {
            DB::beginTransaction();
            try {
                $model = new DeliveryCharge();
                $model->fill($request->all());                        
                $model->save();
                Common::log("Create","Deliverycharge has been saved",$model);
                DB::commit();             
            } catch (\Throwable $e) {
                DB::rollback();
                throw $e;
            }
            return redirect()->route('deliverycharge.index')->with('success', __('admincrud.Delivery Charge added successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Title("Show")
     */
    public function show($id)
    {
        $model = DeliveryCharge::findByKey($id);
        return view('admin.deliverycharge.show', compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Title("Edit")
     */
    public function edit($id,Request $request)
    {
        $model = DeliveryCharge::findByKey($id);
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.deliverycharge.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,DeliveryChargeRequest $request)
    {
         DB::beginTransaction();
        try {
            $model = DeliveryCharge::findByKey($id);        
            $model->fill($request->all());
            $model->save();                  
            Common::log("Deliverycharge Update","Deliveycharge has been updated",$model);  
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('deliverycharge.index')->with('success', __('admincrud.Delivery Charge updated successfully'));
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
        $model = DeliveryCharge::findByKey($id)->delete();
        Common::log("Destroy","DeliveryCharge has been deleted",new DeliveryCharge());
        return redirect()->route('deliverycharge.index')->with('success', __('admincrud.Delivery Charge deleted successfully'));
    }

    /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong')];
            $model = DeliveryCharge::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Delivery Charge status updated successfully')];
            }            
            Common::log("Status Update","Deliverycharge status has been changed",$model);
            return response()->json($response);
        }
    }
}
