<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\BranchRequest;
use App\{
    Http\Controllers\Controller,
    Mail\OutletEmail,
    Area,
    Branch,    
    BranchLang,
    BranchTimeslot,
    BranchDeliveryArea,
    BranchCuisine,
    BranchCategory,
    Category,
    City,
    Cuisine,
    Country,
    DeliveryArea,
    Order,
    Vendor
};
use Auth;
use Common;
use FileHelper;
use DataTables;
use DB;
use HtmlRender;
use Html;
use Hash;


/**
 * @Title('Branch Management')
 */
class BranchController extends Controller
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
            $model = Branch::getAll();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('branch_logo',function ($model) {
                           return HTML::image($model->branch_logo,$model->branch_name,['style'=>'height:50px;']);
                        })
                        ->editColumn('availability_status', function ($model) {
                                return $model->availablityStatus($model->availability_status);
                            })
                        ->editColumn('approved_status', function ($model) {
                                return HtmlRender::approvedStatusColumn($model,'branch.approvedstatus');
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'branch.status');
                         })
                        ->addColumn('action', function ($model) {                                
                                $timeSlot = HtmlRender::actionColumn(
                                    $model,
                                    'branch.timeslot',
                                    [ 'key' => $model->branch_key ],
                                    '<i class="fa fa-clock-o"></i>',
                                    [ 'title' => __('admincrud.Time Slot') ]
                                    );                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'branch.show',
                                    [ 'id' => $model->branch_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'branch.edit',
                                    [ 'id' => $model->branch_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'branch.destroy',
                                    [ 'id' => $model->branch_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$timeSlot$view$edit$delete";
                            })
                        ->rawColumns(['status', 'approved_status','action'])
                        ->toJson();
        }
        $model = new Branch();
        return view('admin.branch.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title('Create')
     */
    public function create(Request $request)
    { 
        $order = new Order();       
        $vendor = new Vendor();
        $model = new Branch();
        $modelLang = new BranchLang();
        $countryList = Country::getCountry();
        $vendorList = Vendor::getVendors();
        $cusineList = Cuisine::getCuisine();
        $categoryList = Category::getCategory();
        $deliveryAreaList = [];
        $existsDeliveryAreas = [];  
        $existsCategory = [];
        $existsCuisine = [];
        $cityList = [];
        $areaList = [];
        $existsCategory = [];
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $modelBranch = $modelLang->fill($request->old());            
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));            
            $deliveryAreaList = DeliveryArea::getDeliveryAreaByArea($request->old('area_id'));
        }        
        return view('admin.branch.create', compact('model','modelLang','countryList',
                'cityList','areaList','vendorList','order','vendor','deliveryAreaList','existsDeliveryAreas','existsCategory',
                'existsCuisine','cusineList','categoryList','deliveryAreaList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Branch  $model
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchRequest $request)
    {  
        DB::beginTransaction();
        try {
            $modelBranch = new Branch();
            $modelBranch = $modelBranch->fill($request->all());
            $vendorName = Vendor::getList()->select('vendor_name','username')->where(['VL.vendor_id' => $request->vendor_id])->first();
            $slugString = ($vendorName->vendor_name." ".$request->branch_name['en']);
            $branchSlug = Str::slug($slugString);
            $existsBranchSlug = Branch::getList()->where(['branch_slug' => $branchSlug])->first();
            
            if($existsBranchSlug != null) {
               $result = $modelBranch->branch_slug = $branchSlug."-1";    
            }
            else {
                $modelBranch->branch_slug = $branchSlug;
            }
            $modelBranch->save(); 
            if ($modelBranch) {
                foreach ($request['delivery_area_id'] as $key => $value) {
                    $modelBranchDeliveryArea = new BranchDeliveryArea();
                    $branchDeliveryArea = [ 'branch_id' => $modelBranch->getKey(), 'delivery_area_id'  => $value];                    
                    $modelBranchDeliveryArea = $modelBranchDeliveryArea->fill($branchDeliveryArea);                    
                    $modelBranchDeliveryArea->save();                    
                }
                
                foreach ($request['cuisine_id'] as $key => $value) {
                    $modelBranchCuisine = new BranchCuisine();
                    $branchCuisine = [ 'branch_id' => $modelBranch->getKey(), 'cuisine_id'  => $value];
                    $modelBranchCuisine = $modelBranchCuisine->fill($branchCuisine);
                    $modelBranchCuisine->save();
                }
            
                foreach ($request['category_id'] as $key => $value) {
                    $modelBranchCategory = new BranchCategory();
                    $branchCategory = [ 'branch_id' => $modelBranch->getKey(), 'category_id'  => $value];
                    $modelBranchCategory = $modelBranchCategory->fill($branchCategory);
                    $modelBranchCategory->save();
                }     

                $languages = Common::getLanguages();
                $data = $request->all();
                foreach ($languages as $langKey => $value) {
                    $data['branch_name'][$langKey] = $request->branch_name[$langKey];
                    $data['branch_address'][$langKey] = $request->branch_address[$langKey];
                    /*foreach ($request->branch_logo as $key => $image){
                        if($langKey == $key){                        
                            $data['branch_logo'][$langKey] = FileHelper::uploadFile($image,BRANCH_LOGO_PATH);
                        }
                    } */
                }
                BranchLang::saveOnLanguage(new BranchLang, $modelBranch->getKey(), $data);
                (new BranchUserController)->store($modelBranch,$request);
            }  
            DB::commit();                         
            $mailData = [ 
                        'branchName' => $request->branch_name,
                        'userName' => $vendorName->username,
                        'email' => $modelBranch->contact_email,
                        'mobileNumber' => $modelBranch->contact_number,
                        'password' => $request->password,
                    ]; 
            Mail::to($modelBranch->contact_email)->send(new OutletEmail($mailData));
            Common::log("Branch Store","Branch has been created",$modelBranch);
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('branch.index')->with('success', __('admincrud.Branch added successfully') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelOrder = new Order();
        $vendor = new Vendor();
        $modelShow = Branch::showBranch($id);
        $explodePayment=explode(',',$modelShow->payment_option);
        return view('admin.branch.show',compact('modelShow','modelOrder','explodePayment','vendor'));
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
        $order = new Order();
        $vendor = new Vendor();       
        $model = Branch::findByKey($id);
        $modelLang = BranchLang::loadTranslation(new BranchLang,$model->branch_id);        
        $countryList = Country::getCountry();
        $vendorList = Vendor::getVendors();
        $cityList = City::getCity($model->country_id);
        $areaList = Area::getArea($model->city_id);
        $deliveryAreaList = DeliveryArea::getDeliveryAreaByArea($model->area_id);
        $existsDeliveryAreas = BranchDeliveryArea::selectDeliveryArea($model->branch_id);
        $cusineList = Cuisine::getCuisine();
        $existsCuisine = BranchCuisine::selectCuisine($model->branch_id);
        $categoryList = Category::getCategory();
        $existsCategory = BranchCategory::selectCategory($model->branch_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));
        }
        return view(
                'admin.branch.update', 
                compact(
                    'model','modelLang','countryList','cityList','areaList','order','vendorList','vendor','deliveryAreaList','existsDeliveryAreas','existsCategory',
                'existsCuisine','cusineList','categoryList','deliveryAreaList'
                )
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Vendor $model
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,BranchRequest $request)
    {       
        DB::beginTransaction();
        try {   
            $modelBranch = new Branch();
            $modelBranch = Branch::findByKey($id);
            $vendorName = Vendor::getList()->select('vendor_name')->where(['VL.vendor_id' => $request->vendor_id])->first();
            $slugString = ($vendorName->vendor_name." ".$request->branch_name['en']);
            $branchSlug = Str::slug($slugString);
            $existsBranchSlug = Branch::where(['branch_slug' => $branchSlug])->where('branch_key','!=',$id)->first();
            
            if($existsBranchSlug != null) {
               $result = $modelBranch->branch_slug = $branchSlug."-1";
            }
            else {
                $modelBranch->branch_slug = $branchSlug;
            }
            $modelBranch = $modelBranch->fill($request->all());
            /* if($modelBranch != null) {
                
                $modelBranch->password = Hash::make($request->password);
            } */
            $modelBranch->save();   
            if ($modelBranch) {    

                /* Delivery Area Edit Start */
                $existsDeliveryAreas = BranchDeliveryArea::selectDeliveryArea($modelBranch->getKey());
                foreach ($request['delivery_area_id'] as $key => $value) {
                    if (in_array($value, $existsDeliveryAreas)) {                        
                        $key = array_search($value, $existsDeliveryAreas);
                        unset($existsDeliveryAreas[$key]);                        
                    } else {
                        $modelBranchDeliveryArea = new BranchDeliveryArea();
                        $fillData = [ 'branch_id' => $modelBranch->getKey(), 'delivery_area_id'  => $value];
                        $modelBranchDeliveryArea = $modelBranchDeliveryArea->fill($fillData);
                        $modelBranchDeliveryArea->save();                                                
                    }
                }
                foreach($existsDeliveryAreas as $key => $value){
                    BranchDeliveryArea::where([ 'branch_id' => $modelBranch->getKey(), 'delivery_area_id'  => $value])->delete();
                }
                /* Delivery Area Edit End */
                
                /*  Branch Cuisine Edit Start */
                $existsCuisine = BranchCuisine::selectCuisine($modelBranch->getkey());
                foreach ($request['cuisine_id'] as $key => $value) {
                    if (in_array($value, $existsCuisine)) {                        
                        $key = array_search($value, $existsCuisine);
                        unset($existsCuisine[$key]);                        
                    } else {
                        $modelBranchCuisine = new BranchCuisine();
                        $fillData = [ 'branch_id' => $modelBranch->getKey(), 'cuisine_id'  => $value];
                        $modelBranchCuisine = $modelBranchCuisine->fill($fillData);
                        $modelBranchCuisine->save();                                                
                    }
                }
                foreach($existsCuisine as $key => $value){
                    BranchCuisine::where([ 'branch_id' => $modelBranch->getKey(), 'cuisine_id'  => $value])->delete();
                }
                /* Branch Cuisine Edit End */

                /*  Branch Category Edit Start */
                $existsCategory = BranchCategory::selectCategory($modelBranch->getKey());
                foreach ($request['category_id'] as $key => $value) {
                    if (in_array($value, $existsCategory)) {                        
                        $key = array_search($value, $existsCategory);
                        unset($existsCategory[$key]);                        
                    } else {
                        $modelBranchCategory = new BranchCategory();
                        $fillData = [ 'branch_id' => $modelBranch->getKey(), 'category_id'  => $value];
                        $modelBranchCategory = $modelBranchCategory->fill($fillData);
                        $modelBranchCategory->save();                                                
                    }
                }
                foreach($existsCategory as $key => $value){
                    BranchCategory::where([ 'branch_id' => $modelBranch->getKey(), 'category_id'  => $value])->delete();
                }
                /* Branch Category Edit End */
                
                $languages = Common::getLanguages();
                $data = $request->all();
                foreach ($languages as $langKey => $value) {
                    $data['branch_name'][$langKey] = $request->branch_name[$langKey];
                    $data['branch_address'][$langKey] = $request->branch_address[$langKey];
                    /*if($request->branch_logo != null) {
                        foreach ($request->branch_logo as $key => $image) {
                            if($langKey == $key) {
                                $modelBranchLang = BranchLang::where(['branch_id' => $modelBranch->branch_id,'language_code' => $langKey])->first();
                                FileHelper::deletefile($modelBranchLang->branch_logo);
                                $data['branch_logo'][$langKey] = FileHelper::uploadFile($image,BRANCH_LOGO_PATH);
                            }
                        }
                    } */
                }
                BranchLang::saveOnLanguage(new BranchLang, $modelBranch->getKey(), $data); 
                $branchUserController = new BranchUserController();
                $branchUserController->update($modelBranch,$request);                
            }
            Common::log("Branch Update","Branch has been updated",$modelBranch);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('branch.index')->with('success', __('admincrud.Branch updated successfully') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @Title('Delete')
     */
    public function destroy($id)
    { 
        $model = Branch::findByKey($id);
        $model = $model->delete();
        Common::log("Destroy","Branch has been deleted",new Branch());
        return redirect()->route('branch.index')->with('success', __('admincrud.Branch deleted successfully') );
    }
    
     /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json     
     * @Assoc('index')
     */
    public function status(Request $request)
    {   
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Branch::findByKey($request->itemkey);
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Branch status updated successfully') ];
            }            
            Common::log("Branch Status","Branch status has been updated",$model);
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
            $model = Branch::where(['branch_key' => $request->itemkey])->first();
            $model->approved_status = $request->approved_status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Branch approved status updated successfully') ];
            }            
            Common::log("Approved Status Update","Branch approved status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getBranch(Request $request)
    {   
        $branchName = [];
        if($request->ajax()){
            $branchName = Branch::getBranch($request->vendor_id);
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $branchName]);    
    } 



    public function getBranchCategory(Request $request)
    {   
        $branchCategoryName = [];
        if($request->ajax()){
            $branchCategoryName = BranchCategory::getBranchCategory($request->vendor_id);
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $branchCategoryName]);
    
    }

    public function getBranchCuisine(Request $request)
    {   
        $branchCuisineName = [];
        if($request->ajax()){
            $branchCuisineName = BranchCuisine::getBranchCuisine($request->vendor_id);
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $branchCuisineName]);
    
    }


    public function timeslot(Request $request)
    {      
        if($request->method() == 'GET') {
            $branchKey = $request->key;                        
            $branch = Branch::findByKey($branchKey);            
            $slotType = $weekDays = BranchTimeslot::getDays();
            foreach($slotType as $key => $value) {
                $delivery = BranchTimeslot::where(['branch_id' => $branch->branch_id, 'timeslot_type' =>ORDER_TYPE_DELIVERY, 'day_no' => $key])->first();
                $pickup = BranchTimeslot::where(['branch_id' => $branch->branch_id, 'timeslot_type' =>ORDER_TYPE_PICKUP_DINEIN, 'day_no' => $key])->first();
                $slotType[$key] = [ 'day' => $value, ORDER_TYPE_DELIVERY => ($delivery != null) ? $delivery->toArray() :  [], ORDER_TYPE_PICKUP_DINEIN => ($pickup != null) ? $pickup->toArray() : [] ];
            }
            return view('admin.branch.timeslot',compact('branchKey','slotType'));
        } else if ($request->method() == 'POST' && $request->ajax()) {
            
            $response = [ 'status' => AJAX_SUCCESS, 'msg' => 'Timeslot has been updated.','data' => []];

            $validatedData = $request->validate([
                'branchKey' => 'required|exists:branch,branch_key',
                'timeslot_type' => 'required|numeric',
                'day_no' => 'required|numeric',
                'start_time' => 'required',
                'end_time' => 'required|greater_than:start_time',
                'status' => 'required|numeric',
            ]);
            $branch = Branch::findByKey($request->branchKey);
            $data = $request->all();
            $data['start_time'] = date('H:i:s',strtotime($request->start_time));
            $data['end_time'] = date('H:i:s',strtotime($request->end_time));
            $data['branch_id'] = $branch->branch_id;                        
            $branchTimeSlot = BranchTimeslot::findByKey($request->branch_timeslot_key);
            if($branchTimeSlot == null) {
                $branchTimeSlot = new BranchTimeslot();
            }
            $branchTimeSlot = $branchTimeSlot->fill($data);
            $branchTimeSlot->save();
            $response['data'] = $branchTimeSlot;
            return response()->json($response);
        }
    }

    public function timeslotnew(Request $request)
    {
        if($request->method() == 'GET') {
            $branchKey = $request->key;
            $branch = Branch::findByKey($branchKey);
            $slotType = BranchTimeslot::getDaysNew();
            foreach($slotType as $dayKey => $dayValue) {
                $emptySlot = [
                    'branch_timeslot_key' => '',
                    'start_time' => '',
                    'end_time' => '',
                    'status' => 0
                ];
                array_push($slotType[$dayKey]['timeslots']['delivery'],$emptySlot);
                $timeSlots = BranchTimeslot::select('branch_timeslot_key','start_time','end_time','status')->where(['branch_id' => $branch->branch_id,'timeslot_type' => ORDER_TYPE_DELIVERY,'day_no' => $dayValue['day_no']])->get()->toArray();
                if(!empty($timeSlots)) {
                    $slotType[$dayKey]['timeslots']['delivery'] = $timeSlots;
                }
                array_push($slotType[$dayKey]['timeslots']['pickup'], $emptySlot);
                $timeSlots = BranchTimeslot::select('branch_timeslot_key','start_time','end_time','status')->where(['branch_id' => $branch->branch_id,'timeslot_type' => ORDER_TYPE_PICKUP_DINEIN,'day_no' => $dayValue['day_no']])->get()->toArray();
                if(!empty($timeSlots)) {
                    $slotType[$dayKey]['timeslots']['pickup'] = $timeSlots;
                }
            }            
            return view('admin.branch.timeslot-new',compact('branch','slotType'));
        } else {
            $response = [ 'status' => AJAX_SUCCESS, 'msg' => 'Timeslot has been updated.','data' => []];

            $validatedData = $request->validate([
                'branch_key' => 'required|exists:branch,branch_key',
                'timeslot_type' => 'required|numeric',
                'day_no' => 'required|numeric',
                'start_time' => 'required',
                'end_time' => 'required|greater_than:start_time',
                'status' => 'required|numeric',
            ]);
            $branch = Branch::findByKey($request->branch_key);
            $data = $request->all();
            $data['start_time'] = date('H:i:s',strtotime($request->start_time));
            $data['end_time'] = date('H:i:s',strtotime($request->end_time));
            $data['branch_id'] = $branch->branch_id;                        
            $branchTimeSlot = BranchTimeslot::findByKey($request->branch_timeslot_key);
            if($branchTimeSlot == null) {
                $branchTimeSlot = new BranchTimeslot();
            }
            $branchTimeSlot = $branchTimeSlot->fill($data);
            $branchTimeSlot->save();
            $response['data'] = $branchTimeSlot;
            return response()->json($response);
        }
    }
}