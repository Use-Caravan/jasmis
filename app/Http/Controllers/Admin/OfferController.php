<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\OfferRequest
};
use App\{
    Offer,
    OfferLang,
    Vendor,
    Branch,
    OfferItem,
    Item
};
use Auth;
use Session;
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Offer Management")
 */
class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {
        $model = new Offer;
        if($request->ajax()) {
            $model = Offer::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'offer.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'offer.show',
                                    [ 'id' => $model->offer_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'offer.edit',
                                    [ 'id' => $model->offer_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'offer.destroy',
                                    [ 'id' => $model->offer_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.offer.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {        
        $model = new Offer(); 
        $modelLang = new OfferLang(); 
        
        if(APP_GUARD === GUARD_ADMIN){
            $vendorList = Vendor::getVendors();
            $branchList = [];
            $itemList = [];
        } else if(APP_GUARD === GUARD_VENDOR) {
            $branchList = Branch::getBranch(Auth::guard(APP_GUARD)->user()->vendor_id);
            $itemList = [];
        } else if(APP_GUARD === GUARD_OUTLET) {
            $branchList = [];
            $itemList = Item::getAllItems(Auth::guard(APP_GUARD)->user()->branch_id);
        }
        $existsItem = [];
        if($request->old()) {
            $model = $model->fill($request->old());
            $branchList = Branch::getBranch($request->old('vendor_id'));
            $itemList = Item::getAllItems($request->old('branch_id'));
            $existsItem = $request->old('item_id');
        }
        return view('admin.offer.create', compact('model','modelLang','existsItem','vendorList','branchList','itemList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request)
    {   
        DB::beginTransaction();
        try {
            $model = new Offer();
            $model->fill($request->all());
            $model->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_datetime));
            $model->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_datetime));
            $model->save();            
            /*  Offer Item Start */
            $existsItems = OfferItem::selectItem($model->getKey());
            foreach ($request->item_id as $key => $value) {
                if (in_array($value, $existsItems)) {
                    $key = array_search($value, $existsItems);
                    unset($existsItems[$key]);
                } else {
                    $offerItem = new OfferItem();
                    $fillData = [ 'offer_id' => $model->getKey(), 'item_id'  => $value];
                    $offerItem = $offerItem->fill($fillData);
                    $offerItem->save();                                                
                }
            }
            foreach($existsItems as $key => $value){
                OfferItem::where([ 'offer_id' => $model->getKey(), 'item_id'  => $value])->delete();
            }
            /* Offer Item End */


            Common::log("Create","Offer has been created",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('offer.index')->with('success', __('admincrud.Offer added successfully') );
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
        $model = Offer::findByKey($id);
        $modelLang = OfferLang::loadTranslation(new OfferLang,$model->offer_id);
        $existsItem = OfferItem::selectOfferItem($model->offer_id);
        
        if(APP_GUARD === GUARD_ADMIN){
            $vendorList = Vendor::getVendors();            
            $branchList = Branch::getBranch($model->vendor_id);
            $itemList = Item::getAllItems($model->branch_id);
        } else if(APP_GUARD === GUARD_VENDOR) {
            $branchList = Branch::getBranch(Auth::guard(APP_GUARD)->user()->vendor_id);
            $itemList = Item::getAllItems(Auth::guard(APP_GUARD)->user()->branch_id);
        } else if(APP_GUARD === GUARD_OUTLET) {
            $branchList = [];
            $itemList = Item::getAllItems(Auth::guard(APP_GUARD)->user()->branch_id);
        }
        if($request->old()) {
            $model = $model->fill($request->old());
            $branchList = Branch::getBranch($request->old('vendor_id'));
            $itemList = Item::getAllItems($request->old('branch_id'));
            $existsItem = $request->old('offer_id');
        }
        return view('admin.offer.update', compact('model','modelLang','existsItem','vendorList','branchList','itemList'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OfferRequest $request, $id)
    {        
        DB::beginTransaction();
        try {
            $model = Offer::findByKey($id);            
            $model->fill($request->all());
            $model->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_datetime));
            $model->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_datetime));
            $model->save();            
            /*  Offer Item Start */
            $existsItems = OfferItem::selectItem($model->getKey());
            foreach ($request->item_id as $key => $value) {
                if (in_array($value, $existsItems)) {
                    $key = array_search($value, $existsItems);
                    unset($existsItems[$key]);
                } else {
                    $offerItem = new OfferItem();
                    $fillData = [ 'offer_id' => $model->getKey(), 'item_id'  => $value];
                    $offerItem = $offerItem->fill($fillData);
                    $offerItem->save();                                                
                }
            }
            foreach($existsItems as $key => $value){
                OfferItem::where([ 'offer_id' => $model->getKey(), 'item_id'  => $value])->delete();
            }
            /* Offer Item End */

            Common::log("Create","Offer has been updated",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('offer.index')->with('success', __('admincrud.Offer updated successfully') );
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
        $model = Offer::findByKey($id)->delete();
        Common::log("Destroy","Offer has been deleted",new Offer());
        return redirect()->route('offer.index')->with('success', __('admincrud.Offer deleted successfully') );
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
            $model = Offer::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Offer status updated successfully')];
            }            
            Common::log("Status Update","Offer status has been changed",$model);
            return response()->json($response);
        }
    }
}
