<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\UserAddress;
use Common;
use DataTables;
use DB;
use Hash;
use HtmlRender;
use Html;

class UserAddressController extends Controller
{
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
            $model = UserAddress::getAllAddress();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name',function ($model) {
                           return ($model->first_name.$model->last_name);
                        })
                        ->editColumn('address',function ($model) {
                           return ($model->address_line_one.','.$model->address_line_two);
                        })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'useraddress.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                                               
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'useraddress.show',
                                    [ 'id' => $model->user_address_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'useraddress.destroy',
                                    [ 'id' => $model->user_address_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$delete";
                            })
                        ->rawColumns(['status','action'])
                        ->toJson();
        }
        return view('admin.useraddress.index');
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
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
     * 
     * @Title('Delete')
     */
    public function destroy($id)    
    {    
        $model = UserAddress::findByKey($id)->delete();
        Common::log("Destroy","User address has been deleted",new UserAddress());
        return redirect()->route('useraddress.index')->with('success', __('admincrud.User address deleted successfully') );
    }

    /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json     
     * @Assoc('index')
     */

    public function status(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = UserAddress::findByKey($request->itemkey);
            $model->status = $request->status; 
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.User address status updated successfully') ];
            }            
            Common::log("User address Status","User address status has been updated",$model);
            return response()->json($response);
        }
    }
}
