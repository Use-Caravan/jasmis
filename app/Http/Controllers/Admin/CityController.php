<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\City;
use App\CityLang;
use App\Country;
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;

/**
 * @Title("City Management")
 */
class CityController extends Controller
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
            $model = City::getAll();           
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'city.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'city.show',
                                    [ 'id' => $model->city_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'city.edit',
                                    [ 'id' => $model->city_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'city.destroy',
                                    [ 'id' => $model->city_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.city.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     * @Title('Create')
     */
    public function create(Request $request)
    {
        $model = new City();
        $modelLang = new CityLang();
        $countryList = Country::getCountry();  
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.city.create', compact('model','modelLang','countryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {        
        DB::beginTransaction();
        try {
            $model = new City();
            $model->fill($request->all());                        
            $model->save(); 
            Common::log("Create","City has been saved",$model);                       
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('city.index')->with('success', __('admincrud.City added successfully') );
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
     * @Title('Edit')
     */
    public function edit($id,Request $request)
    {
        $model = City::findByKey($id);
        $modelLang = CityLang::loadTranslation(new CityLang,$model->city_id);
        $countryList = Country::getCountry();        
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.city.update', compact('model','modelLang','countryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,CityRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = City::findByKey($id);
            $model->fill($request->all());
            $model->save();
            Common::log("Update","City has been updated",$model);                    
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('city.index')->with('success', __('admincrud.Country updated successfully') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title('Delete')
     */
    public function destroy($id)
    {
        $model = City::findByKey($id)->delete();
        Common::log("Destroy","City has been deleted",new City());
        return redirect()->route('city.index')->with('success', __('admincrud.City deleted successfully') );
    }

    /**
     * Change the status specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc('index')
     */
     public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong') ];
            $model = City::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.City status updated successfully') ];
            }            
            Common::log("Status Update","City status has been changed",$model);
            return response()->json($response);
        }
    }
    
    public function getCity(Request $request)
    {
        $cityName = [];
        if($request->ajax()){
            $cityName = City::getCity($request->country_id); 
        }
        return response()->json(['status' => AJAX_SUCCESS,'data' => $cityName]);
    }

    
}
