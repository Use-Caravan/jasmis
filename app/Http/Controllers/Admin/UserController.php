<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
        Controllers\Admin\Controller,
        Requests\Admin\UserRequest   
    };
use App\User;
use Common;
use DataTables;
use DB;
use Hash;
use HtmlRender;
use Html;


class UserController extends Controller
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
            $model = User::getAllUsers();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name',function ($model) {
                           return ($model->first_name.$model->last_name);
                        })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'user.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'user.show',
                                    [ 'id' => $model->user_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'user.edit',
                                    [ 'id' => $model->user_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'user.destroy',
                                    [ 'id' => $model->user_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$view$edit$delete";
                            })
                        ->rawColumns(['status', 'approved_status','action'])
                        ->toJson();
        }
        $model = new User();
        return view('admin.user.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title('Create')
     */
    public function create(Request $request)
    {
        $model = new User();
        if($request->old()) {
            $model = $model->fill($request->old());
        }       
        return view('admin.user.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {  
        DB::beginTransaction();
        try {
            $model = new User();
            $model->fill($request->all());
            $model->password = Hash::make($request->password);
            $model->save();
            Common::log("Create","User has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('user.index')->with('success', __('admincrud.user added successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = User::findByKey($id);
        return view('admin.user.show',compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title('Edit')
     */
    public function edit($id,Request $request)
    {
        $model = User::findByKey($id);
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.user.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = User::findByKey($id);        
            $model->fill($request->all());
            $model->password = Hash::make($request->password);
            $model->save();
            Common::log("Update","User has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('user.index')->with('success', __('admincrud.user updated successfully'));
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
        $model = User::findByKey($id)->delete();
        Common::log("Destroy","user has been deleted",new User());
        return redirect()->route('user.index')->with('success', __('admincrud.user deleted successfully'));
    }

    /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc('index')
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong')];
            $model = User::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.user status updated successfully')];
            }        
            Common::log("Status Update","user status has been changed",$model);    
            return response()->json($response);
        }
    }
}
