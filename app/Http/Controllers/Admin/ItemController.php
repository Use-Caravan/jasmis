<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\ItemRequest   
};
use App\{
    Item,
    ItemLang,
    Vendor,
    Branch,
    BranchCategory,
    BranchCuisine,
    CategoryLang,
    CuisineLang,
    Ingredient,
    ItemGroupMapping,
    IngredientGroup,
    Offer,
    OfferItem,
    ItemCuisine
};
use Common;
use DataTables;
use DB;
use FileHelper;
use Hash;
use HtmlRender;
use Html;


/**
 * @Title("Item Management")
 */
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {         
        $model = new Item();

        if($request->ajax()) {
            $model = Item::getAll();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('approved_status', function ($model) {
                                return HtmlRender::approvedStatusColumn($model,'item.approvedstatus');
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'item.status');
                        })
                        ->editColumn('quickbuy_status', function ($model) {
                                return HtmlRender::quickbuyColumn($model,'item.quickbuystatus');
                        })
                        ->editColumn('newitem_status', function ($model) {
                                $newitemstatus = HtmlRender::newitemColumn($model,'item.newitemstatus');
                                //$newitemstatus = trim($newitemstatus,'"');
                                //return html_entity_decode($newitemstatus);
                                return HtmlRender::newitemColumn($model,'item.newitemstatus');
                        })
                        
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'item.show',
                                    [ 'id' => $model->item_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'item.edit',
                                    [ 'id' => $model->item_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'item.destroy',
                                    [ 'id' => $model->item_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status','approved_status','quickbuy_status','action'])
                        ->toJson();
        }
        $vendorList = Vendor::getVendors();
        $branchList = Branch::getBranch();
        return view('admin.item.index',compact('vendorList','branchList','model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {   
        $model = new Item();
        $modelLang = new ItemLang(); 
        $vendorList = Vendor::getVendors();
        $ingredientGroupList = IngredientGroup::getIngredient();
        if(auth()->guard(GUARD_ADMIN)->check()) { 
            $branchList = [];
        }
        elseif(auth()->guard(GUARD_VENDOR)->check()) {
            $branchList = Branch::getBranch(auth()->guard(GUARD_VENDOR)->user()->vendor_id);
        }      
        $branchCategoryList = [];
        $branchCuisineList = [];
        $existsIngredientGroup = [];    
        $existsCuisines  = [];    
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());            
            $branchList = Branch::getBranch($request->old('vendor_id'));
            $branchCategoryList = BranchCategory::getBranchCategory($request->old('vendor_id'));
            $branchCuisineList = BranchCuisine::getBranchCuisine($request->old('vendor_id'));
            $existsIngredientGroup = $request->old('ingredient_group_id');
            $existsCuisines = $request->old('cuisine_id');
        }       
        return view('admin.item.create', compact('model','modelLang','vendorList','branchCategoryList','branchCuisineList','ingredients','ingredientGroupList','existsIngredientGroup','branchList','existsCuisines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {   
        DB::beginTransaction();
        try {
            // $model = new Item();
            // $model->fill($request->all());
            // $branchId = Branch::findByVendorId($request->vendor_id);
            //$model->branch_id = $branchId->branch_id;
            foreach ($request['branch_id'] as $key => $value) {
                    $model = new Item();
                    $model->fill($request->all());
                    $model->branch_id =$value;
                    $model->save();
                 
            // $model->save(); 
            
                if($model)
                {   
                    if($request['ingredient_group_id'] != '') {    
                        foreach ($request['ingredient_group_id'] as $key => $value) {
                        $modelItemGroupMap = new ItemGroupMapping();
                        $itemGroupData = [
                            'item_id' => $model->getKey(),
                            'ingredient_group_id' => $value, 
                        ];
                        $modelItemGroupMap = $modelItemGroupMap->fill($itemGroupData);                   
                        $modelItemGroupMap->save();
                        }
                    }
                
                    if($request['cuisine_id'] != '') {
                        foreach ($request['cuisine_id'] as $key => $value) {
                        $itemCuisine = new ItemCuisine();
                        $itemCuisineData = [
                            'item_id' => $model->getKey(),
                            'cuisine_id' => $value, 
                        ];
                        $itemCuisine = $itemCuisine->fill($itemCuisineData);                   
                        $itemCuisine->save();
                        }
                    }
                }
            }
            Common::log("Create","Item has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('item.index')->with('success', __('admincrud.Item added successfully') );
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
        $model = Item::findByKey($id);
        $modelLang = ItemLang::loadTranslation(new ItemLang,$model->item_id); 
        $vendorList = Vendor::getVendors();
        $branchList = Branch::getBranch($model->vendor_id);
        $branchCategoryList = BranchCategory::getBranchCategory($model->branch_id);
        $branchCuisineList = BranchCuisine::getBranchCuisine($model->branch_id);
        $ingredientGroupList = IngredientGroup::getIngredient();
        $existsIngredientGroup = ItemGroupMapping::getExistsIngredient($model->item_id);     
        $existsCuisines  = ItemCuisine::getExistsCuisines($model->item_id); 
        
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $existsIngredientGroup = $request->old('ingredient_group_id');
            $existsCuisines = $request->old('cuisine_id');
        }
        return view('admin.item.update', compact('model','modelLang','vendorList','branchCategoryList','branchCuisineList','existsIngredientGroup','ingredients','ingredientGroupList','branchList','existsCuisines'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,ItemRequest $request)
    {
               
        DB::beginTransaction();
        try {
            $model = Item::findByKey($id);
            $model->fill($request->all());
            $model->save();
            /* foreach($request['branch_id'] as $value) {
                $model = Item::findByKey($id);
                $model->fill($request->all());
                $model->branch_id =$value;
                $model->save();
            } */
             if($model) {
               /*  Item Ingredient Group Edit */
                $existsIngredientGroup = ItemGroupMapping::getExistsIngredient($model->getKey());
                if($request['ingredient_group_id'] != '') { 
                    foreach ($request['ingredient_group_id'] as $key => $value) {
                        if (in_array($value, $existsIngredientGroup)) {                        
                            $key = array_search($value, $existsIngredientGroup);
                            unset($existsIngredientGroup[$key]);                        
                        } else {
                            $modelItemgroupmap = new ItemGroupMapping();
                            $fillData = [ 'item_id' => $model->getKey(), 'ingredient_group_id'  => $value];
                            $modelItemgroupmap = $modelItemgroupmap->fill($fillData);
                            $modelItemgroupmap->save();                                                
                        }
                    }
                }
                foreach($existsIngredientGroup as $key => $value){
                    ItemGroupMapping::where([ 'item_id' => $model->getKey(), 'ingredient_group_id'  => $value])->delete();
                } 
                /* Item Ingredient Group Edit End */   


                /*  Item Cuisine Edit */
                $existsItemCusine = ItemCuisine::getExistsCuisines($model->getKey());
               
                if($request['cuisine_id'] != '') { 
                    foreach ($request['cuisine_id'] as $key => $value) {
                        if (in_array($value, $existsItemCusine)) {                        
                            $key = array_search($value, $existsItemCusine);
                            unset($existsItemCusine[$key]);                        
                        } else {
                            $modelItemCuisine = new ItemCuisine();
                            $fillData = [ 'item_id' => $model->getKey(), 'cuisine_id'  => $value];
                            $modelItemCuisine = $modelItemCuisine->fill($fillData);
                            $modelItemCuisine->save();                                                
                        }
                    }
                }                                

                foreach($existsItemCusine as $key => $value){
                    ItemCuisine::where([ 'item_id' => $model->getKey(), 'cuisine_id'  => $value])->delete();
                } 
                /* Item Cuisine Edit End */   
            } 

            Common::log("Update","Item has been updated",$model);    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('item.index')->with('success', __('admincrud.Item updated successfully') );
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
        $model = Item::findByKey($id)->delete();
        Common::log("Destroy","Item has been deleted",new Item);
        return redirect()->route('item.index')->with('success', __('admincrud.Item deleted successfully') );
    }

    /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */
    public function status(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Item::findByKey($request->itemkey);            
            $model->status = $request->status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Item status updated successfully') ];
            }             
            Common::log("Status Update","Item status has been changed",$model);
            return response()->json($response);
        }
    }
    public function quickbuystatus(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Item::findByKey($request->itemkey);            
            $model->quickbuy_status = $request->status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Item quick buy status updated successfully') ];
            }             
            Common::log("Status Update","Item quick buy status has been changed",$model);
            return response()->json($response);
        }
    }

    public function newitemstatus(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Item::findByKey($request->itemkey);            
            $model->newitem_status = $request->status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Item quick buy status updated successfully') ];
            }             
            Common::log("Status Update","Item quick buy status has been changed",$model);
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
            $model = Item::findByKey($request->itemkey);
            $model->approved_status = $request->approved_status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Item approved status updated successfully') ];
            }            
            Common::log("Approved Status Update","Item approved status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getItembyBranch(Request $request)
    {      
        if($request->offer_type == VOUCHER_DISCOUNT_TYPE_PERCENTAGE) {
            $items = Item::getAllItems($request->branch_id);
            $response = ['status' => AJAX_SUCCESS,'data'=> $items ];
        }
        else if ($request->offer_type == VOUCHER_DISCOUNT_TYPE_AMOUNT) {
            $items = Item::getAllItems($request->branch_id,$request->offer_value);
            $response = ['status' => AJAX_SUCCESS,'data'=> $items ];
        }
        return response()->json($response);
    }
    public function getItembyBranchOffer(Request $request)
    {        
        $startDatetime = date('Y-m-d H:i:s', strtotime($request->start_datetime));
        $endDatetime = date('Y-m-d H:i:s', strtotime($request->end_datetime));
        $items = Item::getList()
        ->leftJoin(OfferItem::tableName(), Item::tableName().".item_id",OfferItem::tableName().".item_id")
        ->leftJoin(Offer::tableName(), OfferItem::tableName().".offer_id",Offer::tableName().".offer_id")
        ->where(Offer::tableName().'.end_datetime', '<', $startDatetime)
        ->where(Offer::tableName().'.start_datetime', '>' ,$endDatetime)
        ->where(Offer::tableName().'.branch_id',$request->branch_id)
        ->groupBy(Item::tableName().".item_id")->get();
        if($items != null) {
            $items = $items->toArray();
            $items = array_column($items,'item_name','item_id');
        } else {
            $items = [];
        }        
        $response = ['status' => AJAX_SUCCESS,'data'=> $items ];
        return response()->json($response);
    }


}
