<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\{
        Controllers\Admin\Controller,
        Requests\Admin\VendorRequest   
    };
use App\{
        Mail\VendorEmail,
        Area,
        Branch,
        BranchCategory,
        BranchCuisine,
        BranchDeliveryArea,
        BranchLang,
        Category,
        City,
        Country,
        Cuisine,
        Order,
        Vendor,
        VendorLang,
        DeliveryArea,
        CountryLang,
        CityLang,
        AreaLang
    };
use App\Helpers\Curl;
use Common;
use DataTables;
use DB;
use FileHelper;
use Hash;
use HtmlRender;
use Html;


/**
 * @Title("Vendor Management")
 */
class VendorController extends Controller
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
            //$modelBranch = new Branch();
            $model = Vendor::getAll();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('vendor_logo',function ($model) {
                           return HTML::image($model->vendor_logo,$model->vendor_name,['style'=>'height:50px;']);
                        })
                        ->editColumn('availability_status', function ($model) {
                                return $model->availablityTypes($model->availability_status);
                            })
                        // ->editColumn('approved_status', function ($model) {
                        //     return $model->approvedStatus($model->approved_status);
                        // })
                        ->editColumn('approved_status', function ($model) {
                                return HtmlRender::approvedStatusColumn($model,'vendor.approvedstatus');
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'vendor.status');
                         }) 
                        ->editColumn('popular_status', function ($model) {
                                return HtmlRender::popularColumn($model,'vendor.popularstatus');
                         })
                        ->addColumn('action', function ($model) {                                
                                $timeSlot = HtmlRender::actionColumn(
                                    $model,
                                    'branch.timeslot',
                                    [ 'id' => $model->branch_key ],
                                    '<i class="fa fa-clock-o"></i>',
                                    [ 'title' => __('admincrud.Time Slot') ]
                                    );                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'vendor.show',
                                    [ 'id' => $model->vendor_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'vendor.edit',
                                    [ 'id' => $model->vendor_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'vendor.destroy',
                                    [ 'id' => $model->vendor_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$view$edit$delete";
                            })
                        ->rawColumns(['status', 'approved_status','popular_status','action'])
                        ->toJson();
        }
        $model = new Vendor();
        return view('admin.vendors.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {         
        $order = new Order();       
        $model = new Vendor();
        $modelLang = new VendorLang();
        $modelBranch = new Branch();
        $countryList = Country::getCountry();
       /* $cusineList = Cuisine::getCuisine();
        $categoryList = Category::getCategory();
        $deliveryAreaList = []; */
        $cityList = []; 
        $areaList = [];
        /* $existsDeliveryAreas = [];  
        $existsCategory = [];
        $existsCuisine = [];       */
        if($request->old()) {
            $model = $model->fill($request->old());
            $model->payment_option = implode(',',$request->old('payment_option'));
            $modelLang = $modelLang->fill($request->old());
            $modelBranch = $modelLang->fill($request->old());            
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));            
            $deliveryAreaList = DeliveryArea::getDeliveryAreaByArea($request->old('area_id'));
        }        
        return view('admin.vendors.create', compact('model','modelLang','modelBranch','countryList',
                'cityList','areaList','order','deliveryAreaList','cusineList','categoryList','existsDeliveryAreas','existsCuisine','existsCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {   
        
        DB::beginTransaction();
        try {
            $model = new Vendor();
            $model = $model->fill($request->all());
            $model->password = Hash::make($request->password);
            $model->payment_option = implode(',',$request->payment_option);
            $model->save();                        

            /* if ($model) {
                $branchController = new BranchController();
                $branchController->store($model, $request);
            } */
               // if ($model) {
               //  $nodeVendor = $this->getNodeVenodr($model);
               //  $url = config('webconfig.deliveryboy_url')."/api/v1/vendor?company_id=".config('webconfig.company_id');
               //  $nodeVendor = json_encode($nodeVendor);
               //  $data = Curl::instance()->action('POST')->setUrl($url)->setContentType('text/plain')->send($nodeVendor);
               //  $response = json_decode($data,true);
               //  if($response['status'] != HTTP_SUCCESS) {
               //      DB::rollback();
               //      return redirect()->route('vendor.create')->with('error', __('admincrud.something went wrong') );
               //  }
            // } 
            
            DB::commit();
            // $mailData = [ 
            //         'userName' => $model->username,
            //         'email' => $model->email,
            //         'mobileNumber' => $model->mobile_number,
            //         'password' => $request->password,
            //     ];  
            // Mail::to($model->email)->send(new VendorEmail($mailData));
            Common::log("Create","Vendor has been saved",$model);
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('vendor.index')->with('success', __('admincrud.Vendor added successfully') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //$model = Vendor::findByKey($id);
        $modelOrder = new Order();
        $modelShow = Vendor::showVendors($id);
        return view('admin.vendors.show',compact('model','modelShow','modelOrder'));
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
        
        $order = new Order();       
        $model = Vendor::findByKey($id);
        $modelLang = VendorLang::loadTranslation(new VendorLang,$model->vendor_id);        
        /*$modelBranch = Branch::findByVendorId($model->vendor_id);
        //$explodePayment = explode(',',$model->payment_option);
        //$existpaymentOption = $order->paymentTypes($explodePayment);
         $deliveryAreaList = DeliveryArea::getDeliveryAreaByArea($modelBranch->area_id);
        $existsDeliveryAreas = BranchDeliveryArea::selectDeliveryArea($modelBranch->branch_id);
        $cusineList = Cuisine::getCuisine();
        $existsCuisine = BranchCuisine::selectCuisine($modelBranch->branch_id);
        $categoryList = Category::getCategory();
        $existsCategory = BranchCategory::selectCategory($modelBranch->branch_id); */
        $countryList = Country::getCountry();
        $cityList = City::getCity($model->country_id);
        $areaList = Area::getArea($model->city_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $model->payment_option = implode(',',$request->old('payment_option'));
            $modelLang = $modelLang->fill($request->old());
            //$modelBranch = $modelLang->fill($request->old());            
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));
        }
        return view(
                'admin.vendors.update', 
                compact(
                    'model','modelLang','modelBranch','countryList','cityList','areaList','deliveryAreaList',
                    'cusineList','order','categoryList','existsDeliveryAreas','existsCuisine','existsCategory'
                )
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,VendorRequest $request)
    {   
        DB::beginTransaction();
        try {
            $model = Vendor::findByKey($id);
            $model->fill($request->except('password'));
            $model->payment_option = implode(',',$request->payment_option);
            if($request->password !== null) {
                $model->password = Hash::make($request->password);
            }
            $model->save();           
            
           /*  if ($model) {
                $branchController = new BranchController();
                $branchController->update($model, $request);
            }  */
            
            $nodeVendor = $this->getNodeVenodr($model);
            $url = config('webconfig.deliveryboy_url')."/api/v1/vendor?company_id=".config('webconfig.company_id')."&vendor_key=".$model->vendor_key;
            $nodeVendor = json_encode($nodeVendor);
            $data = Curl::instance()->action('POST')->setUrl($url)->setContentType('text/plain')->send($nodeVendor);
            $response = json_decode($data,true);
            if($response['status'] != HTTP_SUCCESS) {
                DB::rollback();
                return redirect()->route('vendor.edit',[$model->vendor_key])->with('error', __('admincrud.something went wrong') );
            }
            Common::log("Update","Vendor has been updated",$model);
            DB::commit();
        } catch (\Throwable $e) {
           // DB::rollback(); 
            throw $e;
        }
        return redirect()->route('vendor.index')->with('success', __('admincrud.Vendor updated successfully') );
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
        $vendor = Vendor::findByKey($id);        
        //$branch = Branch::where('vendor_id',$vendor->vendor_id)->delete();
        $vendor = $vendor->delete();
        Common::log("Destroy","Vendor has been deleted",new Vendor());
        return redirect()->route('vendor.index')->with('success', __('admincrud.Vendor deleted successfully') );
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
            $model = Vendor::findByKey($request->itemkey);            
            $model->status = $request->status; 
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Vendor status updated successfully') ];
            }            
            Common::log("Vendor Status","Vendor status has been updated",$model);
            return response()->json($response);
        }
    }

     /**
     * Change the approved status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * @Assoc("index")
     */

    /* popular status update */
    public function popularstatus(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Vendor::findByKey($request->itemkey);            
            $model->popular_status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Vendor popular status updated successfully') ];
            }            
            Common::log("Vendor Status","Vendor popular status has been updated",$model);
            return response()->json($response);
        }
    }


    public function approvedStatus(Request $request)
    { 
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Vendor::where(['vendor_key' => $request->itemkey])->first();
            // $modelBranch = Branch::where(['vendor_id'=>$model->vendor_id])->first();
            $model->approved_status = $request->approved_status;
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Vendor approved status updated successfully') ];
            }            
            Common::log("Approved Status Update","Vendor approved status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getBranchCategory(Request $request)
    {   
        $branchCategoryName = [];
        if($request->ajax()){
            $branchCategoryName = BranchCategory::getBranchCategory($request->branch_id);
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $branchCategoryName]);
    
    }

    public function getBranchCuisine(Request $request)
    {   
        $branchCuisineName = [];
        if($request->ajax()){
            $branchCuisineName = BranchCuisine::getBranchCuisine($request->branch_id);
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $branchCuisineName]);
    
    }


    public function getNodeVenodr($model)
    {
        $nodeVendor = [
            'vendor_key' => $model->vendor_key,
            'vendor_country_code' => config('webconfig.country_code'),
            'vendor_mobile' => $model->mobile_number,
            'vendor_email' => $model->email,
            'vendor_email' => $model->email,
            'password' => $model->password,
            'status' => $model->status,
            'payment_type' => 1,
            'latitude' => $model->latitude,
            'longitude' => $model->longitude,
            'web_device_token' => ($model->device_token === null) ? "" : $model->device_token,
            'mobile_device_token' => ($model->device_token === null) ? "" : $model->device_token,
            'secret_key' => "",
            'delivery_charge_base_km' => "",
            'delivery_charge_base' => "",
            'delivery_charge_additional_per_km' => "",
            'availability_status' => 1,
            'nearby_drivers_distance' => 0,
            'notification_mode' => 1,
            'drivers_priority' => 1,
            'address' => [],
        ];                  
        foreach(request()->vendor_name as $key => $value) {
            $country = CountryLang::where([
                'language_code' => $key,
                'country_id' => $model->country_id,
            ])->first();
            $city = CityLang::where([
                'language_code' => $key,
                'city_id' => $model->city_id,
            ])->first();
            $area = AreaLang::where([
                'language_code' => $key,
                'area_id' => $model->area_id,
            ])->first();
            $nodeVendor['address'][] = [
                'lang' => $key,
                'address' => [
                    'name' => $value,
                    'address' => request()->vendor_address[$key],
                    'area' => ($area !== null) ? $area->area_name : "",
                    'city' => ($city !== null) ? $city->city_name : "",
                    'country' => ($country !== null) ? $country->country_name : ""
                ]
            ];
        } 
        return $nodeVendor;
    }

}
