<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\ActivityLog;
use DataTables;
use HtmlRender;


/**
 * @Title("Activity Log Management")
 */
class ActivityLogController extends Controller
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
            $model = ActivityLog::getList();
            return DataTables::eloquent($model)
                        ->addIndexColumn()                        
                        ->addColumn('action', function ($model) {                                
                                $view = HtmlRender::actionColumn(
                                    $model,
                                    'activity-log.show',
                                    [ 'id' => $model->activitylog_id ],
                                    '<i class="fa fa-eye"></i>',
                                    [ 'title' => __('admincommon.View')]
                                    );                                                                
                                $delete = HtmlRender::actionColumn(
                                    $model,
                                    'activity-log.destroy',
                                    [ 'id' => $model->activitylog_id ],
                                    '<i class="fa fa-trash"></i>',                                    
                                    ['class' => "trash", 'title' => __('admincommon.Are you sure?'), 'data-toggle' => "popover", 'data-placement' => "left", 'data-target' => "#delete_confirm", 'data-original-title' => __('admincommon.Are you sure?')],
                                    true
                                    ); 
                                return "$delete";
                            })
                        
                        ->addColumn('ip_address', function ($model) {
                                $data = json_decode((string)$model->properties,true);                                                                
                                return (isset($data['Client IP'])) ? $data['Client IP'] : '';
                            })
                        ->addColumn('checkbox', function ($model) {
                                return '<input id="'.$model->activitylog_id.'" name="log_id[]" type="checkbox" value="'.$model->activitylog_id.'" class="hide checkboxlog">
                                <label for="'.$model->activitylog_id.'" class="checkbox"></label>';
                            })
                        ->addColumn('browser', function ($model) {
                                $data = json_decode((string)$model->properties,true);                                                                
                                return (isset($data['Browser name']) && isset($data['Version'])) ? $data['Browser name'].' - '.$data['Version'] : '';
                            })
                        ->rawColumns(['checkbox','details','action'])
                        ->toJson();
        }
        return view('admin.activity-log.index');
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     * @Title("Delete")
     */ 
    public function destroy($id, Request $request)
    {           
        $logId = explode(',',$request->log_id);
        if($request->log_id != '' && $request->log_id != null &&count($logId) > 0) {
            foreach($logId as $key => $value) {
                $model = ActivityLog::find($value)->delete();
            }
            return redirect()->route('activity-log.index')->with('success', __('admincrud.Log deleted successfully'));
        }        
        return redirect()->route('activity-log.index')->with('warning', __('admincrud.There is no log to delete'));
    }    
}
