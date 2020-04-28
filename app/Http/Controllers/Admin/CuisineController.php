<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Http\Requests\Admin\CuisineRequest;
use App\{
    Helpers\TranslationHelper,
    Cuisine,
    CuisineLang
};
use DataTables;
use Common;
use DB;
use HtmlRender;
use Auth;
use Html;
use Validator;


/**
 * @Title('Cuisine Management')
 */
class CuisineController extends Controller
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
            $model = Cuisine::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'cuisine.status');
                            })
                        ->addColumn('action', function ($model) {
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'cuisine.show',
                                    [ 'id' => $model->cuisine_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'cuisine.edit',
                                    [ 'id' => $model->cuisine_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'cuisine.destroy',
                                    [ 'id' => $model->cuisine_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'),
                                     'data-toggle' => "popover",
                                     'data-placement' => "left",
                                     'data-target' => "#delete_confirm",
                                    'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    );
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.cuisine.index');
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
        $model = new Cuisine();
        $modelLang = new CuisineLang();
        if($request->old()){
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.cuisine.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CuisineRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Cuisine();
            $model->fill($request->all());                        
            $model->save();               
            Common::log("Create","Cuisine has been saved",$model);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('cuisine.index')->with('success', __('admincrud.Cuisine added successfully') );
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
     * 
     * @Title("Edit")
     */
    public function edit($id,Request $request)
    {
        $model = Cuisine::findByKey($id);
        $modelLang = CuisineLang::loadTranslation(new CuisineLang,$model->cuisine_id);
        if($request->old()){
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }        
        return view('admin.cuisine.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, CuisineRequest $request)
    {            
        $model = Cuisine::findByKey($id);        
        DB::beginTransaction();
        try {
            $model->fill($request->all());
            $model->save();            
            Common::log("Update","Cuisine has been updated",$model);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('cuisine.index')->with('success', __('admincrud.Cuisine updated successfully') );
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
        $model = Cuisine::findByKey($id)->delete();  
        Common::log("Destroy","Cuisine has been deleted",new Cuisine());              
        return redirect()->route('cuisine.index')->with('success', __('admincrud.Cuisine deleted successfully') );
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
            $model = Cuisine::findByKey($request->itemkey);            
            $model->status = $request->status;                        
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Cuisine status updated successfully') ];
            }                          
            Common::log("Status Update","Cuisine status has been changed.",$model);
            return response()->json($response);
        }
    }
}
