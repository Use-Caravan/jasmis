<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\NewsletterRequest
};
use App\{ 
    Newsletter
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Newsletter Management")
 */
class NewsletterController extends Controller
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
            $model = Newsletter::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'newsletter.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter.show',
                                    [ 'id' => $model->newsletter_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter.edit',
                                    [ 'id' => $model->newsletter_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'newsletter.destroy',
                                    [ 'id' => $model->newsletter_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$view$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.newsletter.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new Newsletter();
        if($request->old()) {
            $model = $model->fill($request->old());
        }       
        return view('admin.newsletter.create', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsletterRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Newsletter();
            $model->fill($request->all());                        
            $model->save();
            Common::log("Create","Newsletter has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('newsletter.index')->with('success', __('admincrud.Newsletter added successfully'));
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
        $model = Newsletter::findByKey($id);
        return view('admin.newsletter.show', compact('model'));
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
        $model = Newsletter::findByKey($id);
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.newsletter.update', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,NewsletterRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = Newsletter::findByKey($id);        
            $model->fill($request->all());
            $model->save();                  
            Common::log("Newsletter Update","Newsletter has been updated",$model);  
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('newsletter.index')->with('success', __('admincrud.Newsletter updated successfully'));
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
        $model = Newsletter::findByKey($id)->delete();
        Common::log("Destroy","Newsletter has been deleted",new Newsletter());
        return redirect()->route('newsletter.index')->with('success', __('admincrud.Newsletter deleted successfully'));
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
            $model = Newsletter::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Newsletter status updated successfully')];
            }            
            Common::log("Status Update","Newsletter status has been changed",$model);
            return response()->json($response);
        }
    }
    
}
