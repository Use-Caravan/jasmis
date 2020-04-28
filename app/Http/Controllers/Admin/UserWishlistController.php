<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\UserWishlist;
use Common;
use DataTables;
use DB;
use Hash;
use HtmlRender;
use Html;


class UserWishlistController extends Controller
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
            $model = UserWishlist::getAllWishlist();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name',function ($model) {
                           return ($model->first_name.$model->last_name);
                        })
                        ->editColumn('status', function ($model) {
                            return HtmlRender::statusColumn($model,'userwishlist.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'userwishlist.show',
                                    [ 'id' => $model->user_wishlist_id ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'userwishlist.edit',
                                    [ 'id' => $model->user_wishlist_id ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'userwishlist.destroy',
                                    [ 'id' => $model->user_wishlist_id ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$delete";
                            })
                        ->rawColumns(['status','action'])
                        ->toJson();
        }
        return view('admin.userwishlist.index');
    
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
     * 
     * @Title('Delete')
     */
    public function destroy($id)
    {
        $model = UserWishlist::findByKey($id)->delete();
        Common::log("Destroy","Wishlist has been deleted",new UserWishlist());
        return redirect()->route('userwishlist.index')->with('success', __('admincrud.Wishlist deleted successfully') );
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
            $model = UserWishlist::findByKey($request->itemkey);
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.User wishlist status updated successfully') ];
            }            
            Common::log("Wishlist Status","User wishlist status has been updated",$model);
            return response()->json($response);
        }
    }
}
