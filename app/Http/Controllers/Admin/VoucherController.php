<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Controller\Admin,
    Requests\Admin\VoucherRequest
};
use App\{
    User,
    Voucher,
    Vendor,
    Branch,
    VoucherBeneficiary
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;
use Carbon;
use DateTime;


/**
 * @Title("Voucher Management")
 */
class VoucherController extends Controller
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
            $model = Voucher::getList();    
            //print_r($model->get());exit;        
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('apply_promo_for', function ($model) {                                                         
                            return $model->selectApplyPromo($model->apply_promo_for);
                        })
                        ->editColumn('app_type', function ($model) { 
                            return $model->selectAppTypes($model->app_type);
                        })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'voucher.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'voucher.show',
                                    [ 'id' => $model->voucher_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'voucher.edit',
                                    [ 'id' => $model->voucher_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'voucher.destroy',
                                    [ 'id' => $model->voucher_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        $model = new Voucher();
        return view('admin.voucher.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {   
        $model = new Voucher();        
        $branchList = Branch::getBranch();
        $userList = User::getUsers();
        $explodeAppType = [];
        $modeltime = '';
        $existsShopBenificiary = [];
        $existsUserBenificiary = [];
        if($request->old()) {
            $model = $model->fill($request->old());
         }       
        return view('admin.voucher.create', compact('model','branchList','explodeAppType','modeltime','existsUserBenificiary','existsShopBenificiary','userList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request)
    {   
        DB::beginTransaction();
        try {
            /** Discount value should be less than maximum redeem amount validation **/
            /*if( $request->discount_type == 2 )
            {
                $max_redeem_amount = $request->max_redeem_amount;
                $discount_value = $request->value;

                if( $discount_value > $max_redeem_amount )
                {
                    return redirect()->route('voucher.create')->with('error', __('admincrud.Discount value should be less than maximum redeem amount') );
                }
            }*/
            $model = new Voucher();
            $model->fill($request->all());
            $expiryDate = $request->expiry_date;

            if( $request->discount_type == 2 )
                $model->max_redeem_amount = 0;
            else
                $model->max_redeem_amount =($request->max_redeem_amount == null) ? 0 : $request->max_redeem_amount;

            $model->expiry_date = date('Y-m-d H:i:s', strtotime($expiryDate));
            $model->promo_code = ($request->promo_code === null) ? (Common::generateRandomString('voucher', 'promo_code', 8)) : $request->promo_code;
            $appType = implode(",",$request->app_type);
            $model->app_type = $appType;
            $model->limit_of_use =($request->limit_of_use == null) ? 0 : $request->limit_of_use;
            $model->save();            
            if($request->shopbeneficiary_id !== null) {
                foreach ($request->shopbeneficiary_id as $value) {
                    $modelVoucherBeneficiary = new VoucherBeneficiary();
                    $modelVoucherBeneficiaryDetails = ['voucher_id' => $model->getKey(),'beneficiary_type' =>  PROMO_FOR_ALL_SHOPS,'beneficiary_id' => $value];
                    $modelVoucherBeneficiary = $modelVoucherBeneficiary->fill($modelVoucherBeneficiaryDetails);
                    $modelVoucherBeneficiary->save();
                }
            }
            if($request->userbeneficiary_id !== null) {     
                foreach ($request->userbeneficiary_id as $value) {
                    $modelVoucherBeneficiary = new VoucherBeneficiary();
                    $modelVoucherBeneficiaryDetails = ['voucher_id' => $model->getKey(),'beneficiary_type' =>  PROMO_FOR_ALL_USERS,'beneficiary_id' => $value];
                    $modelVoucherBeneficiary = $modelVoucherBeneficiary->fill($modelVoucherBeneficiaryDetails);
                    $modelVoucherBeneficiary->save();
                }
            }
           
            Common::log("Create","Voucher has been saved",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('voucher.index')->with('success', __('admincrud.Voucher added successfully') );
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
        $model = Voucher::findByKey($id);
        $model->expiry_date = date('m/d/Y h:i A', strtotime($model->expiry_date));        
        $explodeAppType = explode(',', $model->app_type);

        $branchList = Branch::getBranch();
        $userList = User::getUsers();
        $existsShopBenificiary = VoucherBeneficiary::existsShopBeneficiary($model->getKey());
        $existsUserBenificiary = VoucherBeneficiary::existsUserBeneficiary($model->getKey());
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.voucher.update', compact('model','branchList','explodeAppType','existsUserBenificiary','existsShopBenificiary','userList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,VoucherRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = Voucher::findByKey($id);    

            $model->fill($request->all());

            if( $request->discount_type == 2 )
                $model->max_redeem_amount = 0;
            else
                $model->max_redeem_amount =($request->max_redeem_amount == null) ? 0 : $request->max_redeem_amount;
    
            $expiryDate = $request->expiry_date;
            $model->expiry_date = date('Y-m-d H:i:s', strtotime($expiryDate));
            $appType = implode(",",$request->app_type);
            $model->app_type = $appType;
            $model->save();
            if ($model) {
               if($request->shopbeneficiary_id !== null) {
                    /* Voucher Beneficiary Edit Start */
                    $existsShopBenificiary = VoucherBeneficiary::existsShopBeneficiary($model->getKey());                
                    foreach ($request->shopbeneficiary_id as $key => $value) {
                        if (in_array($value, $existsShopBenificiary)) {
                            $key = array_search($value, $existsShopBenificiary);
                            unset($existsShopBenificiary[$key]);                        
                        } else {
                            $modelVoucherBeneficiary = new VoucherBeneficiary();
                            $fillData = [ 'voucher_id' => $model->getKey(),'beneficiary_type' => PROMO_FOR_ALL_SHOPS, 'beneficiary_id'  => $value];
                            $modelVoucherBeneficiary = $modelVoucherBeneficiary->fill($fillData);
                            $modelVoucherBeneficiary->save();                                                
                        }
                    }
                    foreach($existsShopBenificiary as $key => $value){
                        VoucherBeneficiary::where([ 'voucher_id' => $model->getKey(),'beneficiary_type' => PROMO_FOR_ALL_SHOPS,'beneficiary_id'  => $value])->delete();
                    }
                    /* Voucher Beneficiary Edit End */  
                }

                if($request->userbeneficiary_id !== null) { 
                    /* Shop Beneficiary Edit Start */
                    $existsUserBeneficiary = VoucherBeneficiary::existsUserBeneficiary($model->getKey());
                    foreach ($request->userbeneficiary_id as $key => $value) {
                        if (in_array($value, $existsUserBeneficiary)) {
                            $key = array_search($value, $existsUserBeneficiary);
                            unset($existsUserBeneficiary[$key]);                        
                        } else {
                            $modelVoucherBeneficiary = new VoucherBeneficiary();
                            $fillData = [ 'voucher_id' => $model->getKey(),'beneficiary_type' => PROMO_FOR_ALL_USERS, 'beneficiary_id'  => $value];
                            $modelVoucherBeneficiary = $modelVoucherBeneficiary->fill($fillData);
                            $modelVoucherBeneficiary->save();                                                
                        }
                    }
                    foreach($existsUserBeneficiary as $key => $value){
                        VoucherBeneficiary::where([ 'voucher_id' => $model->getKey(),'beneficiary_type' => PROMO_FOR_ALL_USERS,'beneficiary_id'  => $value])->delete();
                    }
                    /* Shop Beneficiary Edit End */  
                }
            } 
            Common::log("Update","Voucher has been updated",$model);     
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('voucher.index')->with('success', __('admincrud.Voucher updated successfully') );
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
        $model = Voucher::findByKey($id)->delete();
        Common::log("Destroy","Voucher has been deleted",new Voucher());
        return redirect()->route('voucher.index')->with('success', __('admincrud.Voucher deleted successfully') );
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
            $model = Voucher::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Voucher status updated successfully') ];
            }            
            Common::log("Status Update","Voucher status has been changed",$model);
            return response()->json($response);
        }
    }
}
