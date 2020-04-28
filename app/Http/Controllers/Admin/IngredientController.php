<?php
namespace App\Http\Controllers\Admin;   


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\IngredientRequest
};
use App\{ 
    Ingredient,
    IngredientLang,
    Vendor
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;


/**
 * @Title("Ingredient Management")
 */
class IngredientController extends Controller
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
            $model = Ingredient::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'ingredient.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient.show',
                                    [ 'id' => $model->ingredient_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient.edit',
                                    [ 'id' => $model->ingredient_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient.destroy',
                                    [ 'id' => $model->ingredient_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.ingredient.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new Ingredient();
        $modelLang = new IngredientLang();         
        $vendorList = Vendor::getVendors();
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.ingredient.create', compact('model','modelLang','vendorList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientRequest $request)
    {
        
        DB::beginTransaction();
        try {
            $model = new Ingredient();
            $model->fill($request->all());                        
            $model->save();
            Common::log("Create","Ingredient has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('ingredient.index')->with('success', __('admincrud.Ingredient added successfully') );
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
        $model = Ingredient::findByKey($id);
        $modelLang = IngredientLang::loadTranslation(new IngredientLang,$model->ingredient_id);
        $vendorList = Vendor::getVendors();
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.ingredient.update', compact('model','modelLang','vendorList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,IngredientRequest $request)
    {        
        DB::beginTransaction();
        try {
            $model = Ingredient::findByKey($id);        
            $model->fill($request->all());
            $model->save();                  
            Common::log("Ingredient Update","Ingredient has been updated",$model);  
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('ingredient.index')->with('success', __('admincrud.Ingredient updated successfully') );
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
        $model = Ingredient::findByKey($id)->delete();
        Common::log("Destroy","Ingredient has been deleted",new Ingredient());
        return redirect()->route('ingredient.index')->with('success', __('admincrud.Ingredient deleted successfully') ); 
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
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Ingredient::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Ingredient status updated successfully') ];
            }            
            Common::log("Status Update","Ingredient status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getIngredientsDepandsOnVendor(Request $request)
    {
        $ingredients = Ingredient::getActiveIngredients(null, $request->vendor_id); 
        return response()->json(['status' => AJAX_SUCCESS, 'data' => $ingredients]);
    }
}
