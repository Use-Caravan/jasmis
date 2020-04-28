<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use FileHelper;
use App\Http\Requests\Admin\CategoryRequest;
use DataTables;
use App\Category;
use App\CategoryLang;
use DB;

use Common;
use HtmlRender;
use Html;


/**
 * @Title('Category Management')
 */
class CategoryController extends Controller
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
            $model = Category::getList();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('is_main_category', function ($model) { 
                                $categoryType = "Main Category";    
                                if ($model->is_main_category != MAIN_CATEGORY) {
                                    $categoryType = "Sub Category";
                                }
                                return $categoryType;
                            })
                            
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'category.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'category.show',
                                    [ 'id' => $model->category_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'category.edit',
                                    [ 'id' => $model->category_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'category.destroy',
                                    [ 'id' => $model->category_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.category.index');
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
        $model = new Category();
        $modelLang = new CategoryLang();
        $mainCategories = Category::getCategory();
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }            
        return view('admin.category.create', compact('model','modelLang','mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {           
        DB::beginTransaction();
        try {
            $model = new Category();
            $model->fill($request->all());
            if($request->file('category_image')) {
                $files = $request->file('category_image');
                $destinationPath = APP_CATEGORY_PATH; 
                $categoryImage = FileHelper::uploadFile($files,$destinationPath);
                $model->category_image = $categoryImage;
            }
            $model->save();
            Common::log("Create","Category has been saved",$model);                        
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('category.index')->with('success', __('admincrud.Category added successfully') );
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
     * 
     * @Title('Edit')
     */
    public function edit($id,Request $request)
    {
        $model = Category::findByKey($id);
        $modelLang = CategoryLang::loadTranslation(new CategoryLang,$model->category_id);
        $mainCategories = Category::getCategory();        
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.category.update', compact('model','modelLang','mainCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,CategoryRequest $request)
    {
               
        DB::beginTransaction();
        try {
            $model = Category::findByKey($id); 
            $model->fill($request->all());
            $model->save();
            Common::log("Update","Category has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('category.index')->with('success', __('admincrud.Category updated successfully') );
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
        $model = Category::findByKey($id)->delete();
        Common::log("Destroy","Category has been deleted",new Category());
        return redirect()->route('category.index')->with('success', __('admincrud.Category deleted successfully') );
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
            $model = Category::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Category status updated successfully') ];
            }           
            Common::log("Status Update","Category status has been changed",$model); 
            return response()->json($response);
        }
    }
    
}
