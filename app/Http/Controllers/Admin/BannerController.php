<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BannerRequest;
use DataTables;
use App\Banner;
use App\BannerLang;
use Common;
use DB;
use FileHelper;
use HtmlRender;
use Html;
use Storage;


/**
 * @Title('Banner Management')
 */
class BannerController extends Controller
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
            $model = Banner::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('banner_file',function ($model) {
                           return HTML::image($model->banner_file,$model->banner_name,['style'=>'height:50px;']);
                        })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'banner.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'banner.show',
                                    [ 'id' => $model->banner_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'banner.edit',
                                    [ 'id' => $model->banner_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'banner.destroy',
                                    [ 'id' => $model->banner_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.banner.index');
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
        $model = new Banner();
        $modelLang = new BannerLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());         
            //echo '<pre>'; var_dump($request->old()); exit;
        }       
        return view('admin.banner.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {                   
        DB::beginTransaction();
        try {
            $model = new Banner();
            $model->fill($request->all());
            $model->save();
            Common::log("Banner Store","Banner save has been changed",$model);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('banner.index')->with('success', __('admincrud.Banner added successfully') );
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
     * @Title('Edit')
     */
    public function edit($id,Request $request)
    {
        $model = Banner::findByKey($id);
        $modelLang = BannerLang::loadTranslation(new BannerLang,$model->banner_id); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.banner.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,BannerRequest $request)
    {                   
        $model = Banner::findByKey($id);        
        DB::beginTransaction();
        try {
            $model->fill($request->all());
            $model->save();
            Common::log("Banner Update","Banner has been updated",$model);                        
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('banner.index')->with('success', __('admincrud.Banner updated successfully') );
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
        $model = Banner::findByKey($id)->delete();        
        Common::log("Banner Delete","Banner has been deleted",new Banner());
        return redirect()->route('banner.index')->with('success', __('admincrud.Banner deleted successfully') );
    }

    /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc('index')
     */
     public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Banner::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Banner status updated successfully') ];
            }            
            Common::log("Banner Status","Banner status has been changed",$model);
            return response()->json($response);
        }
    }
}
