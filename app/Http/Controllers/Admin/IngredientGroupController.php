<?php
namespace App\Http\Controllers\Admin;   


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\IngredientGroupRequest
};
use App\{
    Ingredient,
    IngredientLang,
    IngredientGroup,
    IngredientGroupMapping,
    IngredientGroupLang,
    Vendor
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;


/**
 * @Title("Ingredient Group Management")
 */
class IngredientGroupController extends Controller
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
            $model = IngredientGroup::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'ingredient-group.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient-group.show',
                                    [ 'id' => $model->ingredient_group_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient-group.edit',
                                    [ 'id' => $model->ingredient_group_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'ingredient-group.destroy',
                                    [ 'id' => $model->ingredient_group_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.ingredient-group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new IngredientGroup();
        $modelLang = new IngredientGroupLang(); 
        $vendorList = Vendor::getVendors();
        $existsIngredients = [];        
        $ingredients = Ingredient::getActiveIngredients(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $existsIngredients = IngredientGroupMapping::getExistsIngredients(null,$request->old('price'));            
            $ingredients = Ingredient::getActiveIngredients($existsIngredients);
        }       
        return view('admin.ingredient-group.create', compact('model','modelLang','vendorList','ingredients','existsIngredients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IngredientGroupRequest $request)
    {   
       
        DB::beginTransaction();
        try {
            $model = new IngredientGroup();
            $model->fill($request->all());
            $model->save();
            foreach($request->price as $key => $value){
                $ingredientGroupMapping = new IngredientGroupMapping();
                $fillables = ['ingredient_group_id' => $model->getKey(), 'ingredient_id' => $key, 'price' => $value, 'default_status' => 1 ];
                $ingredientGroupMapping = $ingredientGroupMapping->fill($fillables);
                $ingredientGroupMapping->save();
            }   
            Common::log("Create","Ingredient group has been saved",$model);         
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('ingredient-group.index')->with('success', __('admincrud.Ingredient group added successfully') );
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
        $model = IngredientGroup::findByKey($id);        
        $modelLang = IngredientGroupLang::loadTranslation(new IngredientGroupLang,$model->ingredient_group_id);
        $existsIngredients = IngredientGroupMapping::getExistsIngredients($model->ingredient_group_id);
        $ingredients = Ingredient::getActiveIngredients($existsIngredients);         
        $vendorList = Vendor::getVendors();
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $existsIngredients = IngredientGroupMapping::getExistsIngredients(null,$request->old('price'));            
            $ingredients = Ingredient::getActiveIngredients($existsIngredients);            
        }
        return view('admin.ingredient-group.update', compact('model','modelLang','vendorList','existsIngredients','ingredients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,IngredientGroupRequest $request)
    {
        DB::beginTransaction();
        try {            
            $model = IngredientGroup::findByKey($id);    
            $model->fill($request->all());
            $model->save(); 
            $existsIngredients = IngredientGroupMapping::getExistsIngredients($model->getKey());
            $existsIngredients = array_column($existsIngredients,'ingredient_id');
            foreach($request->price as $key => $value){
                if (in_array($key, $existsIngredients)) {
                    $ingredientGroupMapping = IngredientGroupMapping::where(['ingredient_group_id' => $model->getKey(), 'ingredient_id' => $key])->first();                    
                    $fillables = ['price' =>  $value, 'default_status' => 1 ];
                    $ingredientGroupMapping = $ingredientGroupMapping->fill($fillables);
                    $ingredientGroupMapping->save();
                    $fkey = array_search($key, $existsIngredients);
                    unset($existsIngredients[$fkey]);
                } else {
                    $ingredientGroupMapping = new IngredientGroupMapping();
                    $fillables = ['ingredient_group_id' => $model->getKey(), 'ingredient_id' => $key, 'price' => $value, 'default_status' => 1 ];
                    $ingredientGroupMapping = $ingredientGroupMapping->fill($fillables);
                    $ingredientGroupMapping->save();
                }
            }
            foreach($existsIngredients as $key => $value){
                IngredientGroupMapping::where(['ingredient_group_id' => $model->getKey(), 'ingredient_id' => $value])->delete();
            }

            $languages = Common::getLanguages();
            foreach ($languages as $langKey => $value) {
                IngredientGroupLang::saveOnLanguage(new IngredientGroupLang, $model->getKey(), $langKey, $request->all());
            }
            Common::log("Update","Ingredient group has been updated",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('ingredient-group.index')->with('success', __('admincrud.Ingredient group updated successfully') );
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
        $model = IngredientGroup::findByKey($id)->delete();
        Common::log("Destroy","Ingredient group has been deleted",new IngredientGroup());
        return redirect()->route('ingredient-group.index')->with('success', __('admincrud.Ingredient group deleted successfully') );
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
            $model = IngredientGroup::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Ingredient group status updated successfully') ];
            }            
            Common::log("Status Update","Ingredientgroup status has been changed",$model);
            return response()->json($response);
        }
    }    
}
