<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AddressTypeRequest;
use DataTables;
use App\AddressType;
use App\AddressTypeLang;
use DB;
use HtmlRender;
use Common;
use Html;

/**
 * @Title("Address Type Management")
 */
class AddressTypeController extends Controller
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
            $model = AddressType::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'addresstype.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'addresstype.show',
                                    [ 'id' => $model->address_type_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'addresstype.edit',
                                    [ 'id' => $model->address_type_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'addresstype.destroy',
                                    [ 'id' => $model->address_type_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit$delete";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.addresstype.index');
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
        $model = new AddressType();
        $modelLang = new AddressTypeLang(); 
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }       
        return view('admin.addresstype.create', compact('model','modelLang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressTypeRequest $request)
    {                             
        DB::beginTransaction();
        try {
            $model = new AddressType();
            $model->fill($request->all());                                    
            $model->save();
            Common::log("Create","Addresstype Save has been changed",$model);             
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('addresstype.index')->with('success', __('admincrud.Address type added successfully') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *       
     */
    public function show($id)
    {
        echo "work";
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
        $model = AddressType::findByKey($id);        
        $modelLang = AddressTypeLang::loadTranslation(new AddressTypeLang,$model->address_type_id);        
        if($request->old()) {
            $model = $model->fill($request->old());
            $modelLang = $modelLang->fill($request->old());
        }
        return view('admin.addresstype.update', compact('model','modelLang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,AddressTypeRequest $request)
    {             
        DB::beginTransaction();
        try {
            $model = AddressType::findByKey($id);
            $model->fill($request->all());
            $model->save();
            Common::log("Update","Addresstype has been updated",$model);            
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('addresstype.index')->with('success', __('admincrud.Address type updated successfully') );
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
        $model = AddressType::findByKey($id)->delete();
        Common::log("Destroy","Addresstype has been deleted",new AddressType);
        return redirect()->route('addresstype.index')->with('success', __('admincrud.Address type deleted successfully') );
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
            $response = ['status' => AJAX_FAIL, 'msg' => __('admincommon.Something went wrong')];
            $model = AddressType::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Address type status updated successfully')];
            }
            Common::log("Status Update","Addresstype status has been changed",$model);
            return response()->json($response);
        }
    }
}
