<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\BranchReview;
use Common;
use DataTables;
use DB;
use Hash;
use HtmlRender;
use Html;

/**
 * @Title("Rating Management")
 */
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List)
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $model = BranchReview::getAllReviews();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('name',function ($model) {
                           return ($model->first_name.$model->last_name);
                        })
                        ->editColumn('approved_status', function ($model) {
                                return HtmlRender::approvedStatusColumn($model,'review.approvedstatus');
                            })
                        ->editColumn('status', function ($model) {
                            return HtmlRender::statusColumn($model,'review.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'review.destroy',
                                    [ 'id' => $model->branch_review_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$delete";
                            })
                        ->rawColumns(['status','approved_status','action'])
                        ->toJson();
        }
        $model = new BranchReview();
        return view('admin.review.index',compact('model'));
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
        $model = BranchReview::findByKey($id)->delete();
        Common::log("Destroy","Review has been deleted",new BranchReview());
        return redirect()->route('review.index')->with('success', __('admincrud.Review deleted successfully') );
    }

      /**
     * Change the  status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    
    public function status(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = BranchReview::findByKey($request->itemkey);
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Review status updated successfully') ];
            }            
            Common::log("Review Status","Review status has been updated",$model);
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
       
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = BranchReview::findByKey($request->itemkey);
            $model->approved_status = $request->approved_status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Review approved status updated successfully') ];
            }            
            Common::log("Approved Status Update","Review approved status has been changed",$model);
            return response()->json($response);
        }
    }
}
