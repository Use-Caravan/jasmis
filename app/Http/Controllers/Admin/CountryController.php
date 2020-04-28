<?php
namespace App\Http\Controllers\Admin;   


use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CountryRequest;
use DataTables;
use App\Country;
use App\CountryLang;
use Common;
use DB;
use HtmlRender;
use Html;

/**
 *  @Title("Country Management")
 */
class CountryController extends Controller
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
            $model = Country::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'country.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'country.show',
                                    [ 'id' => $model->country_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'country.edit',
                                    [ 'id' => $model->country_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'country.destroy',
                                    [ 'id' => $model->country_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Delete'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.country.index');
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
        $model = new Country();
        $modelLang = new CountryLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.country.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        
        DB::beginTransaction();
        try {
            $model = new Country();
            $model->fill($request->all());                        
            $model->save();
            Common::log("Create","Country has been saved",$model);               
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('country.index')->with('success', __('admincrud.Country added successfully') );
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
     * @Title("edit")
     */
    public function edit($id,Request $request)
    {
        $model = Country::findByKey($id);
        $modelLang = CountryLang::loadTranslation(new CountryLang,$model->country_id);
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.country.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,CountryRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = Country::findByKey($id);        
            $model->fill($request->all());
            $model->save();
            Common::log("Update","Country has been updated",$model);        
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('country.index')->with('success', __('admincrud.Country updated successfully') );
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
        $model = Country::findByKey($id)->delete();
        Common::log("Destroy","Country has been deleted",new Country());
        return redirect()->route('country.index')->with('success', __('admincrud.Country deleted successfully') );
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
            $model = Country::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Country status updated successfully') ];
            }           
            Common::log("Status Update","Country status has been changed",$model); 
            return response()->json($response);
        }
    }

}
