<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller\Admin;
use DataTables;
use App\Enquiry;
use Common;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Enquiry Management")
 */
class EnquiryController extends Controller
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
            $model = Enquiry::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'enquiry.status');
                            })
                        ->addColumn('action', function ($model) {
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'enquiry.show',
                                    [ 'id' => $model->enquiry_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'enquiry.edit',
                                    [ 'id' => $model->enquiry_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'enquiry.destroy',
                                    [ 'id' => $model->enquiry_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    );
                                return "$view$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.enquiry.index');
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
        $model = Enquiry::findByKey($id);
        return view('admin.enquiry.show',compact('model'));
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
        $model = Enquiry::findByKey($id)->delete();
        Common::log("Destroy","Enquiry has been deleted",new Enquiry());
        return redirect()->route('enquiry.index')->with('success', __('admincrud.Enquiry deleted successfully') );
    }

    /**
     * Change the status specified resource.
     * @param  instance Request $reques      
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Enquiry::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Enquiry status updated successfully') ];
            }            
            Common::log("Status Update","Enquiry staus has been changed",$model);
            return response()->json($response);
        }
    }


}
