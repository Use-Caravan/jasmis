<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\FaqRequest
};
use App\{ 
    Faq,
    FaqLang
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;


/**
 * @Title("FAQ Management")
 */
class FaqController extends Controller
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
            $model = Faq::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'faq.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'faq.show',
                                    [ 'id' => $model->faq_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'faq.edit',
                                    [ 'id' => $model->faq_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'faq.destroy',
                                    [ 'id' => $model->faq_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.faq.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new Faq();
        $modelLang = new FaqLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.faq.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Faq();
            $model->fill($request->all());                        
            $model->save();
            Common::log("Create","FAQ has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('faq.index')->with('success', __('admincrud.FAQ added successfully'));
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
     * @Title("Edit")
     */
    public function edit($id,Request $request)
    {
        $model = Faq::findByKey($id);
        $modelLang = FaqLang::loadTranslation(new FaqLang,$model->faq_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.faq.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,FaqRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = Faq::findByKey($id);        
            $model->fill($request->all());
            $model->save();
            Common::log("Update","FAQ has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('faq.index')->with('success', __('admincrud.FAQ updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Title("Destroy")
     */
    public function destroy($id)
    {
        $model = Faq::findByKey($id)->delete();
        Common::log("Destroy","FAQ has been deleted",new Faq());
        return redirect()->route('faq.index')->with('success', __('admincrud.FAQ deleted successfully'));
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
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincrud.Something went wrong')];
            $model = Faq::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.FAQ status updated successfully')];
            }        
            Common::log("Status Update","FAQ status has been changed",$model);
            return response()->json($response);
        }
    }
}
