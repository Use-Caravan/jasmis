<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\CmsRequest
};
use App\{ 
    Cms,
    CmsLang
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;
use FileHelper;

/**
 * @Title('CMS Management')
 */
class CmsController extends Controller
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
            $model = Cms::getList();    
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('cms_content', function ($model) {
                                return $model->cms_content;
                        })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'cms.status');
                        })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'cms.show',
                                    [ 'id' => $model->cms_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'cms.edit',
                                    [ 'id' => $model->cms_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = '';
                                if($model->position != 1 && $model->position != 2 && $model->position != 3) {
                                    $delete = HtmlRender::actionColumn(
                                        $model,
                                        'cms.destroy',
                                        [ 'id' => $model->cms_key ],
                                        '<i class="fa fa-trash"></i>',                                    
                                        ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                        true
                                    ); 
                                }
                                return "$view$edit$delete";
                            })
                        ->rawColumns(['cms_content','status', 'action'])
                        ->toJson();
        }
        return view('admin.cms.index');
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
        $model = new Cms();
        $modelLang = new CmsLang(); 
        $sectionslist = [];
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.cms.create', compact('model','modelLang','sectionslist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CmsRequest $request)
    {   
        DB::beginTransaction();
        try {
            $model = new Cms();
            $model->fill($request->all());
            $model->slug = str_slug($request->title['en'],'-'); 
            $section = implode(",",$request->section);
            $model->section = $section;
            if($request->file('ldpi_image_path')) {
                $files = $request->file('ldpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $idpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->ldpi_image_path = $idpiImage;
            }
            if($request->file('mdpi_image_path')) {
                $files = $request->file('mdpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $mdpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->mdpi_image_path = $mdpiImage;
            }
            if($request->file('hdpi_image_path')) {
                $files = $request->file('hdpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $hdpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->hdpi_image_path = $hdpiImage;
            }
            if($request->file('xhdpi_image_path')) {
                $files = $request->file('xhdpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $xhdpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->xhdpi_image_path = $xhdpiImage;
            }
            if($request->file('xxhdpi_image_path')) {
                $files = $request->file('xxhdpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $xxhdpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->xxhdpi_image_path = $xxhdpiImage;
            }
            if($request->file('xxxhdpi_image_path')) {
                $files = $request->file('xxxhdpi_image_path');
                $destinationPath = APP_CMS_PATH; 
                $xxxhdpiImage = FileHelper::uploadFile($files,$destinationPath);
                $model->xxxhdpi_image_path = $xxxhdpiImage;
            }
            $model->save();
            Common::log("Create","CMS has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('cms.index')->with('success', __('admincrud.CMS added successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title('Show')
     */
    public function show($id)
    {   
        $model = Cms::findByKey($id);
        $modelLang = CmsLang::where(['cms_id' => $model->cms_id])->first();
        return view('admin.cms.show',compact('modelLang'));
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
        $model = Cms::findByKey($id);
        $modelLang = CmsLang::loadTranslation(new CmsLang,$model->cms_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.cms.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response    
     */
    public function update($id,CmsRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = Cms::findByKey($id);        
            $model->fill($request->all());
            $model->slug = str_slug($request->title['en'],'-'); 
            $model->save();
            Common::log("Update","CMS has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('cms.index')->with('success', __('admincrud.CMS updated successfully'));
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
        $model = Cms::findByKey($id)->delete();
        Common::log("Destroy","CMS has been deleted",new Cms());
        return redirect()->route('cms.index')->with('success', __('admincrud.CMS deleted successfully'));
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
            $model = Cms::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.CMS status updated successfully')];
            }        
            Common::log("Status Update","CMS status has been changed",$model);    
            return response()->json($response);
        }
    }

}
