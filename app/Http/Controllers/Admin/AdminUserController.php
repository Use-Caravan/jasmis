<?php
namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\{
    Controllers\Controller\Admin,
    Requests\Admin\AdminUserRequest
};
use App\{
    CModel,
    AdminUser,
    AdminUserLang,
    Role
};
use Common;
use DataTables;
use DB;
use HtmlRender;
use Html;
use Hash;

/**
 * @Title("Admin User Management")
 */
class AdminUserController extends Controller
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
        $roles = Role::getRoleFilter();        
        if($request->ajax()) {
            $model = AdminUser::getAll();            
            return DataTables::eloquent($model)
                        ->addIndexColumn()                        
                        ->editColumn('status', function ($model) {
                                return HtmlRender::statusColumn($model,'admin-user.status');
                            })
                       ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'admin-user.show',
                                    [ 'id' => $model->admin_user_key ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View') ]
                                    );                                
                                $edit = HtmlRender::actionColumn(
                                    $model,
                                    'admin-user.edit',
                                    [ 'id' => $model->admin_user_key ],
                                    '<i class="fa fa-pencil"></i>',
                                    [ 'title' => __('admincommon.Edit')]
                                    );
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'admin-user.destroy',
                                    [ 'id' => $model->admin_user_key ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?') ],
                                    true
                                    ); 
                                return "$view$edit";
                            })
                        ->rawColumns(['status', 'action'])
                        ->toJson();
        }
        return view('admin.admin-user.index',compact('roles'));
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
        $model = new AdminUser();
        /* $modelLang = new AdminUserLang(); */
        $roleName = Role::getRoles();        
        if($request->old()) {
            $model = $model->fill($request->old());
            /* $modelLang = $modelLang->fill($request->old()); */
        }       
        return view('admin.admin-user.create', compact('model','modelLang','roleName','existRole'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserRequest $request)
    {           
        DB::beginTransaction();
        try {
            $model = new AdminUser();
            $model = $model->fill($request->all()); 
            $model->user_type = SUB_ADMIN;     
            $model->password = Hash::make($request->password);                    
            $model->save();                        
            /* CModel::saveOnLanguage(new AdminUserLang, $model->getKey(), $request->all()); */
            Common::log("Create","Admin user Save has been changed",$model);
            DB::commit();             
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return redirect()->route('admin-user.index')->with('success', __('admincrud.Admin user added successfully') );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title("Show")
     */
    public function show($id, Request $request)
    {
        $role = new Role();
        $model = new AdminUser();
        $model = AdminUser::getList()  
        ->addSelect($role->getTable().".role_name")      
        ->leftJoin($role->getTable(),$role->getTable().".role_id",'=',$model->getTable().".role_id")
        ->where('admin_user_key' ,$id)->first();        
        return view('admin.admin-user.show', compact('model'));
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
        $model = AdminUser::findByKey($id);
        /* $modelLang = AdminUserLang::loadTranslation(new AdminUserLang,$model->admin_user_id);  */
        $roleName = Role::getRoles();
        if($request->old()) {
            $model = $model->fill($request->old());
            /* $modelLang = $modelLang->fill($request->old()); */
        }
        return view('admin.admin-user.update', compact('model','modelLang','roleName'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,AdminUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $model = AdminUser::findByKey($id);        
            $model->fill($request->except('password'));
            if( $request->password != '') {
                $model->password = Hash::make($request->password);
            }
            $model->save();
            /* Common::log("Update","Admin user update has been changed",$model);         */
            DB::commit();
            CModel::saveOnLanguage(new AdminUserLang, $model->getKey(), $request->all());
        } catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return redirect()->route('admin-user.index')->with('success', __('admincrud.Admin user updated successfully') );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title("Destroy")
     */
    public function destroy($id)
    {
        $model = AdminUser::findByKey($id)->delete();
        Common::log("Destroy","Admin user destroy has been changed",new AdminUser);
        return redirect()->route('admin-user.index')->with('success', __('admincrud.Admin user deleted successfully') );
    }

     /**
     * Change the status specified resource.
     * @param  instance Request $reques 
     * @return \Illuminate\Http\Response Json
     * 
     * @Assoc("index")
     */
    public function status(Request $request)
    {
        if($request->ajax()){
            $response = ['status' => AJAX_FAIL, 'msg' => 'Something went wrong'];
            $model = AdminUser::findByKey($request->itemkey);            
            $model->status = $request->status;            
            if($model->save()){
                $response = ['status' => AJAX_SUCCESS,'msg'=> __('admincrud.Admin user status updated successfully') ];
            } 
            Common::log("Status Update","Admin user status has been updated",$model);           
            return response()->json($response);
        }
    }
}
