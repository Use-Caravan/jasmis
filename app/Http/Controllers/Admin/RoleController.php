<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Admin\Controller,
    Requests\Admin\RoleRequest
};
use App\{ 
    Role,
    Helpers\AccessRules
};
use Session;
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;
use function GuzzleHttp\json_encode;

/**
 * @Title("Role Management")
 */
class RoleController extends Controller
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
            $model = Role::getList();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'role.status');
                            })
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'role.show',
                                    [ 'id' => $model->role_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'role.edit',
                                    [ 'id' => $model->role_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit') ]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'role.destroy',
                                    [ 'id' => $model->role_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$edit";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @Title("Create")
     */
    public function create(Request $request)
    {
        $this->getRules();
        $model = new Role();
        $ruleList = $this->getRules();
        if($request->old()) {
            $model = $model->fill($request->old());
        }
        return view('admin.role.create', compact('model','ruleList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = new Role();
            $model->fill($request->all());                        
            $model->user_type = ROLE_USER_ADMIN;
            $model->save();
            Common::log("Create","Role has been created",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('role.index')->with('success', __('admincrud.Role added successfully') );
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
        if($id == GUARD_VENDOR) {
            $model = Role::where('user_type',ROLE_USER_VENDOR)->first();
            if($model === null) {
                $model = new Role();
                $model->fill(['role_name' => GUARD_VENDOR,'user_type' => ROLE_USER_VENDOR, 'status' => ITEM_ACTIVE]);
                $model->save(); 
            }            
        } else if($id == GUARD_OUTLET) {
            $model = Role::where('user_type',ROLE_USER_OUTLET)->first();
            if($model === null) {
                $model = new Role();
                $model->fill(['role_name' => GUARD_OUTLET,'user_type' => ROLE_USER_OUTLET, 'status' => ITEM_ACTIVE]);
                $model->save(); 
            }            
        } else {
            $model = Role::findByKey($id);
        }
        $ruleList = $this->getRules();        
        if($request->old()) {
            $model = $model->fill($request->old());
        }        
        return view('admin.role.update', compact('model','ruleList'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
        DB::beginTransaction();
        try {
            $model = Role::findByKey($id);        
            $model->fill($request->all());
            $model->save();                  
            Common::log("Update","Role has been updated",$model);  
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('role.index')->with('success', __('admincrud.Role updated successfully') );
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
        $model = Role::findByKey($id)->delete();
        Common::log("Destroy","Role has been deleted",new Role());
        return redirect()->route('role.index')->with('success', __('admincrud.Role deleted successfully') );
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
            $model = Role::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Role status updated successfully')];
            }            
            Common::log("Status Update","Role status has been changed",$model);
            return response()->json($response);
        }
    }

    public function getRules()
    {
        $rules = (array)AccessRules::getRulesIndex()['methods'];        
        $ruleList = [];        
        foreach ($rules as $module) {
            $item = [
                'id' => $module['slug'],
                'text' => $module['title'],
                'children' => [],
                'state' => [
                    'opened' => true,
                ]
            ];

            foreach ((array)$module['methods'] as $method) {
                $item['children'][] = [
                    'text' => $method['title'],
                    'id' => $method['slug'],
                    'state' => [
                    ]
                ];
            }
            $ruleList[] = $item;
        }        
        return $ruleList;
    }
}
