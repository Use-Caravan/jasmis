<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\LoyaltyLevelRequest;
use DataTables;
use App\LoyaltyLevel;
use App\LoyaltyLevelLang;
use Common;
use DB;
use HtmlRender;
use FileHelper;
use Html;

/**
 * @Title("Loyalty Level Management")
 */
class LoyaltyLevelController extends Controller
{
    /**
     * Display a listing of the resource.     
     * @return \Illuminate\Http\Response
     * @Title("List")
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $model = LoyaltyLevel::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'loyaltylevel.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltylevel.show',
                                    [ 'id' => $model->loyalty_level_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltylevel.edit',
                                    [ 'id' => $model->loyalty_level_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'loyaltylevel.destroy',
                                    [ 'id' => $model->loyalty_level_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Delete'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.loyaltylevel.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new LoyaltyLevel();
        $modelLang = new LoyaltyLevelLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.loyaltylevel.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoyaltyLevelRequest $request)
    {
        /** Check loyalty point per BD is greater than higher loyalty level's OR lesser than higher loyalty level's  or not **/
            $loyalty_level_high = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('loyalty_point_per_bd', '<', $request->loyalty_point_per_bd)->count();

            $loyalty_level_low = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('loyalty_point_per_bd', '>', $request->loyalty_point_per_bd)->count();
            
            if( $loyalty_level_high > 0 )
            {
                return redirect()->route('loyaltylevel.create')->with('error', __("admincrud.Loyalty point per BD should not greater than higher loyalty level") );
            }
            else if( $loyalty_level_low > 0 )
            {
                return redirect()->route('loyaltylevel.create')->with('error', __("admincrud.Loyalty point per BD should not lesser than lower loyalty level") );
            }

            /** Check redeem amount per point is greater than higher loyalty level's OR lesser than higher loyalty level's  or not **/
            $redeem_amount_per_point_high = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('redeem_amount_per_point', '<', $request->redeem_amount_per_point)->count();

            $redeem_amount_per_point_low = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('redeem_amount_per_point', '>', $request->redeem_amount_per_point)->count();
            
            if( $redeem_amount_per_point_high > 0 )
            {
                return redirect()->route('loyaltylevel.create')->with('error', __("admincrud.Redeem amount per point should not greater than higher loyalty level") );
            }
            else if( $redeem_amount_per_point_low > 0 )
            {
                return redirect()->route('loyaltylevel.create')->with('error', __("admincrud.Redeem amount per point should not lesser than lower loyalty level") );
            }

        DB::beginTransaction();
        try {
            $model = new LoyaltyLevel();
            $model->fill($request->all());
            if($request->file('card_image')) {
                $files = $request->file('card_image');
                $destinationPath = APP_LOYALTY_LEVEL_PATH; 
                $cardImage = FileHelper::uploadFile($files,$destinationPath);
                $model->card_image = $cardImage;
            }
            if($request->file('popup_image')) {
                $files = $request->file('popup_image');
                $destinationPath = APP_POPUP_IMAGE_PATH; 
                $popupImage = FileHelper::uploadFile($files,$destinationPath);
                $model->popup_image = $popupImage;
            }
            $model->save();
            Common::log("Create","Loyalty Level has been saved",$model);               
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('loyaltylevel.index')->with('success', __('admincrud.Loyalty Level added successfully') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
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
        $model = LoyaltyLevel::findByKey($id);
        $modelLang = LoyaltyLevelLang::loadTranslation(new LoyaltyLevelLang,$model->loyalty_level_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.loyaltylevel.update', compact('model','modelLang'));
    }

    public function checkLoyaltyPointByLevel(Request $request)
    {
        $loyalty_level_high_count = 0;
        //echo $request->ajax();exit;
        if($request->ajax()){
            $type = $request->type;

            if( $type == "loyalty_point_per_bd" )
            {
                $loyalty_level_high_count = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('loyalty_point_per_bd', '<', $request->loyalty_point_per_bd)->count();

                $loyalty_level_low_count = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('loyalty_point_per_bd', '>', $request->loyalty_point_per_bd)->count();

                $high_count = $loyalty_level_high_count;
                $low_count = $loyalty_level_low_count;

                $loyalty_level_high_count = 0;
                $loyalty_level_low_count = 0;
                $redeem_amount_per_point_high_count = 0;
                $redeem_amount_per_point_low_count = 0;
            }
            else if( $type == "redeem_amount_per_point" )
            {
                $redeem_amount_per_point_high_count = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('redeem_amount_per_point', '<', $request->redeem_amount_per_point)->count();

                $redeem_amount_per_point_low_count = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('redeem_amount_per_point', '>', $request->redeem_amount_per_point)->count();
                
                $high_count = $redeem_amount_per_point_high_count;
                $low_count = $redeem_amount_per_point_low_count;

                $loyalty_level_high_count = 0;
                $loyalty_level_low_count = 0;
                $redeem_amount_per_point_high_count = 0;
                $redeem_amount_per_point_low_count = 0;
            }
            else if( $type == "both" )
            {
                $loyalty_level_high_count = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('loyalty_point_per_bd', '<', $request->loyalty_point_per_bd)->count();
                //echo $loyalty_level_high_count;exit;

                $loyalty_level_low_count = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('loyalty_point_per_bd', '>', $request->loyalty_point_per_bd)->count();
                
                $redeem_amount_per_point_high_count = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('redeem_amount_per_point', '<', $request->redeem_amount_per_point)->count();

                $redeem_amount_per_point_low_count = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('redeem_amount_per_point', '>', $request->redeem_amount_per_point)->count();
                
                $high_count = 0;
                $low_count = 0;
            }
        }
        //print_r(['status' => AJAX_SUCCESS,'high_count' => $high_count,'low_count' => $low_count,'loyalty_level_high_count' => $loyalty_level_high_count,'loyalty_level_low_count' => $loyalty_level_low_count,'redeem_amount_per_point_high_count' => $redeem_amount_per_point_high_count,'redeem_amount_per_point_low_count' => $redeem_amount_per_point_low_count]);exit;

        return response()->json(['status' => AJAX_SUCCESS,'high_count' => $high_count,'low_count' => $low_count,'loyalty_level_high_count' => $loyalty_level_high_count,'loyalty_level_low_count' => $loyalty_level_low_count,'redeem_amount_per_point_high_count' => $redeem_amount_per_point_high_count,'redeem_amount_per_point_low_count' => $redeem_amount_per_point_low_count]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,LoyaltyLevelRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = LoyaltyLevel::findByKey($id);        

            /** Check loyalty point per BD is greater than higher loyalty level's OR lesser than higher loyalty level's  or not **/
            $loyalty_level_high = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('loyalty_point_per_bd', '<', $request->loyalty_point_per_bd)->count();

            $loyalty_level_low = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('loyalty_point_per_bd', '>', $request->loyalty_point_per_bd)->count();
            
            if( $loyalty_level_high > 0 )
            {
                return redirect()->route('loyaltylevel.edit',[ 'id' => $model->loyalty_level_key ])->with('error', __("admincrud.Loyalty point per BD should not greater than higher loyalty level") );
            }
            else if( $loyalty_level_low > 0 )
            {
                return redirect()->route('loyaltylevel.edit',[ 'id' => $model->loyalty_level_key ])->with('error', __("admincrud.Loyalty point per BD should not lesser than lower loyalty level") );
            }

            /** Check redeem amount per point is greater than higher loyalty level's OR lesser than higher loyalty level's  or not **/
            $redeem_amount_per_point_high = LoyaltyLevel::where('to_point', '>', $request->to_point)->where('redeem_amount_per_point', '<', $request->redeem_amount_per_point)->count();

            $redeem_amount_per_point_low = LoyaltyLevel::where('to_point', '<', $request->to_point)->where('redeem_amount_per_point', '>', $request->redeem_amount_per_point)->count();
            
            if( $redeem_amount_per_point_high > 0 )
            {
                return redirect()->route('loyaltylevel.edit',[ 'id' => $model->loyalty_level_key ])->with('error', __("admincrud.Redeem amount per point should not greater than higher loyalty level") );
            }
            else if( $redeem_amount_per_point_low > 0 )
            {
                return redirect()->route('loyaltylevel.edit',[ 'id' => $model->loyalty_level_key ])->with('error', __("admincrud.Redeem amount per point should not lesser than lower loyalty level") );
            }

            //$model = LoyaltyLevel::findByKey($id);        

            $existsImage       = $model->card_image;
            $existspopupImage  = $model->popup_image;
            $model->fill($request->all());            
            if($request->file('card_image')) {
                $files = $request->file('card_image');
                $destinationPath = APP_LOYALTY_LEVEL_PATH; 
                $cardImage = FileHelper::uploadFile($files,$destinationPath);
                FileHelper::deleteFile($existsImage);
                $model->card_image = $cardImage;
            }
            if($request->file('popup_image')) {
                $files = $request->file('popup_image');
                $destinationPath = APP_POPUP_IMAGE_PATH; 
                $popupImage = FileHelper::uploadFile($files,$destinationPath);
                FileHelper::deleteFile($existspopupImage);
                $model->popup_image = $popupImage;
            }
            $model->save();
            Common::log("Update","Loyalty Level has been updated",$model);        
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('loyaltylevel.index')->with('success', __('admincrud.Loyalty Level updated successfully') );
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
        $model = LoyaltyLevel::findByKey($id)->delete();
        Common::log("Destroy","Loyalty Level has been deleted",new LoyaltyLevel());
        return redirect()->route('loyaltylevel.index')->with('success', __('admincrud.Loyalty Level deleted successfully'));
    }
     /**
     * Change the status specified resource.
     * @param  instance Request $reques      
     * @return \Illuminate\Http\Response Json     
     * @Assoc("index")
     */
     public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = LoyaltyLevel::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Loyalty Level status updated successfully') ];
            }           
            Common::log("Status Update","Loyalty Level status has been changed",$model); 
            return response()->json($response);
        }
    }

    public function getRandomNumber($len = "16")
    {   
        $better_token = $code=sprintf("%0".$len."d", mt_rand(1, str_pad("", $len,"9")));
        return $better_token;

       /*  $rand   = 0;
        for ($i = 0; $i<15; $i++) 
            {
                $rand .= mt_rand(0,9);
            }
        return $rand; */
    
    }

    
}
