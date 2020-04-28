<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\LoyaltyPointRequest
};
use App\{ 
    LoyaltyPoint
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Loyalty Point Management")
 */
class LoyaltyPointController extends Controller
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
            $model = LoyaltyPoint::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'loyaltypoint.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltypoint.show',
                                    [ 'id' => $model->loyalty_point_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltypoint.edit',
                                    [ 'id' => $model->loyalty_point_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltypoint.destroy',
                                    [ 'id' => $model->loyalty_point_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.loyaltypoint.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new LoyaltyPoint();
        if($request->old()) {
            $model = $model->fill($request->old());
        }       
        return view('admin.loyaltypoint.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $model = new LoyaltyPoint();
            $model->fill($request->all());                        
            $model->save();
            Common::log("Create","Loyaltypoint has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('loyaltypoint.index')->with('success', __('admincrud.Loyalty Point added successfully'));
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
        $model = LoyaltyPoint::findByKey($id);
        /* return view('admin.loyaltypoint.show', compact('model')); */
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
        $model = LoyaltyPoint::findByKey($id);
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.loyaltypoint.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,LoyaltyPointRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = LoyaltyPoint::findByKey($id);        
            $model->fill($request->all());
            $model->save();                  
            Common::log("Loyaltypoint Update","Loyalty Point has been updated",$model);  
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('loyaltypoint.index')->with('success', __('admincrud.Loyalty Point updated successfully'));
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
        $model = LoyaltyPoint::findByKey($id)->delete();
        Common::log("Destroy","Loyalty Point has been deleted",new LoyaltyPoint());
        return redirect()->route('loyaltypoint.index')->with('success', __('admincrud.Loyalty Point deleted successfully'));
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
            $model = LoyaltyPoint::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Loyalty Point status updated successfully')];
            }            
            Common::log("Status Update","Loyaltypoint status has been changed",$model);
            return response()->json($response);
        }
    }
}
