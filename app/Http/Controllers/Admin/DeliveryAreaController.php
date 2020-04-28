<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Controller\Admin,
    Requests\Admin\DeliveryAreaRequest
};
use App\{
    Country,
    City,
    Area,
    DeliveryArea,
    DeliveryAreaLang
};

use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;


/**
 * @Title("Delivery Area Management")
 */
class DeliveryAreaController extends Controller
{
    /**
     * Display a listing of the resource.     
     * @return \Illuminate\Http\Response
     * 
     * @Title("List")
     */
    public function index(Request $request)
    {               
        if($request->ajax()) {        	
            $model = DeliveryArea::getAll();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('zone_type', function ($model) {
                                return DeliveryArea::getZonetype($model->zone_type);
                            })
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'delivery-area.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'delivery-area.show',
                                    [ 'id' => $model->delivery_area_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'delivery-area.edit',
                                    [ 'id' => $model->delivery_area_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'delivery-area.destroy',
                                    [ 'id' => $model->delivery_area_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        $model = new DeliveryArea();
        return view('admin.delivery-area.index',compact('model'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $model = new DeliveryArea();
        $modelLang = new DeliveryAreaLang();
        $countryList = Country::getCountry();
        $cityList = [];  
        $areaList = [];  
        if($request->old()) {            
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));
        } 
        return view('admin.delivery-area.create',compact('model','modelLang','countryList','cityList','areaList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryAreaRequest $request)
    {        
        /** Test mode */        
        /* POLYGON((50.866753 5.686455, 50.859819 5.708942, 50.851475 5.722675, 50.841611 5.720615, 50.834023 5.708427, 50.840744 5.689373, 50.858735 5.673923, 50.866753 5.686455)) */

        if((int)$request->zone_type === DELIVERY_AREA_ZONE_POLYGON) {
            
            $polValue = json_decode($request->zone_latlng);
            $polArray = [];

            foreach ($polValue as $pol) {                
                $polArray[] = implode(' ', $pol);
            }
            $polArray[] = implode(' ', reset($polValue));
        }

       DB::beginTransaction();
        try {  
            $model = new DeliveryArea();
            $model->fill($request->except('zone_latlng'));
            if((int)$request->zone_type === DELIVERY_AREA_ZONE_POLYGON) {
                $model->zone_latlng = \DB::raw("GeomFromText('POLYGON((".implode(', ', $polArray)."))')");
               
            }

            $model->save();  
            Common::log("Create","Deliveyarea has been saved",$model);                              
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('delivery-area.index')->with('success', __('admincrud.Delivery area added successfully') );
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
     * @Title("Edit")
     */
    public function edit($id,Request $request)
    {

        $model = DeliveryArea::select([
            '*',
            DB::raw('ST_AsText(zone_latlng) as zone_latlng')
        ])->where('delivery_area_key',$id)->first();
        
        if($model->zone_type === DELIVERY_AREA_ZONE_POLYGON && $model->zone_latlng !== null) {
            $text = str_replace('POLYGON(','',$model->zone_latlng);            
            $text = preg_match('#\((.*?)\)#', $text, $match);
            $model->zone_latlng = json_encode(explode(',',$match[1]));
            //$model->zone_latlng = json_decode(trim()),true);
        }
        $modelLang = DeliveryAreaLang::loadTranslation(new DeliveryAreaLang,$model->delivery_area_id);
        $countryList = Country::getCountry();
        $cityList = City::getCity($model->country_id);
        $areaList = Area::getArea($model->city_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());            
            $cityList = City::getCity($request->old('country_id'));
            $areaList = Area::getArea($request->old('city_id'));
        } 
        return view('admin.delivery-area.update',compact('model','modelLang','countryList','cityList','areaList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DeliveryAreaRequest $request, $id)
    {
        if((int)$request->zone_type === DELIVERY_AREA_ZONE_POLYGON) {
            
            $polValue = json_decode($request->zone_latlng);
            $polArray = [];

            foreach ($polValue as $pol) {                
                $polArray[] = implode(' ', $pol);
            }
            $polArray[] = implode(' ', reset($polValue));
        }

        DB::beginTransaction();
        try {
            $model = DeliveryArea::findByKey($id);
            $model->fill($request->except('zone_latlng'));
            if((int)$request->zone_type === DELIVERY_AREA_ZONE_POLYGON) {
                $model->zone_latlng = \DB::raw("GeomFromText('POLYGON((".implode(', ', $polArray)."))')");
            }            
            $model->save();
            Common::log("Update","Deliveyarea has been updated",$model);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('delivery-area.index')->with('success', __('admincrud.Delivery area updated successfully') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title("Delete")
     */
    public function destroy($id)
    {
        $model = DeliveryArea::findByKey($id)->delete();
        Common::log("Destroy","Deliveyarea has been deleted",new DeliveryArea());
        return redirect()->route('delivery-area.index')->with('success', __('admincrud.Delivery Area deleted successfully') ); 
    }

     /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * 
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc("index")
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = DeliveryArea::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Delivery Area status updated successfully') ];
            }            
            Common::log("Status Update","Deliveyarea status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getDeliveryArea(Request $request)
    {
        $deliveryAreaName = [];
         if($request->ajax()){
            $deliveryAreaName = DeliveryArea::getDeliveryAreaByArea($request->area_id);                      
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $deliveryAreaName]);
    }

}
