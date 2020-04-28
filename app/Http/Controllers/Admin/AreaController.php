<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\AreaRequest
};
use App\{
    Area,
    AreaLang,
    City,
    Country
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("Area Management")
 */
class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title("List")
     */
    public function index(Request $request)
    {       
        if($request->ajax()) {          
            $model = Area::getAll();
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'area.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'area.show',
                                    [ 'id' => $model->area_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'area.edit',
                                    [ 'id' => $model->area_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'area.destroy',
                                    [ 'id' => $model->area_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.area.index');
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
        $model = new Area();
        $modelLang = new AreaLang();
        $countryList = Country::getCountry();
        $cityList = [];  
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
            $cityList = City::getCity($request->old('country_id'));
        }
        return view('admin.area.create', compact('model','modelLang','countryList','cityList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AreaRequest $request)
    {   
        DB::beginTransaction();
        try {
            $model = new Area();
            $model->fill($request->all());            
            $model->save();
            Common::log("Create","Area save has been changed",$model);                                    
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('area.index')->with('success', __('admincrud.Area added successfully') );
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
        $model = Area::findByKey($id);
        $modelLang = AreaLang::loadTranslation(new AreaLang,$model->area_id);
        $countryList = Country::getCountry();
        $cityList = City::getCity($model->country_id); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.area.update', compact('model','modelLang','countryList','cityList'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,AreaRequest $request)
    {        
        DB::beginTransaction();
        try {
            $model = Area::findByKey($id);
            $model->fill($request->all());
            $model->save();
            Common::log("Update","Area has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('area.index')->with('success', __('admincrud.Area updated successfully') );
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
        $model = Area::findByKey($id)->delete();
        Common::log("Destroy","Area has been deleted",new Area());
        return redirect()->route('area.index')->with('success', __('admincrud.Area deleted successfully') );
    }

    /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc("index")
     */
     public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = Area::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Area status updated successfully')];
            }        
            Common::log("Status Update","Area status has been changed",$model);    
            return response()->json($response);
        }
    }


    /**
     * 
     */
    public function getArea(Request $request)
    {
        if($request->ajax()){            
            $model = Area::getArea($request->city_id); 
            $response = ['status' => AJAX_SUCCESS, 'data' => $model];            
            return response()->json($response);
        }
    }
}
