<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BranchUser;
use Common;
use DB;
use Hash;
use HtmlRender;

class BranchUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($modelBranch,$request)
    {   
        
         DB::beginTransaction();
        try {
            $model = new BranchUser();
            $model->branch_id = $modelBranch->branch_id;
            $model->vendor_id = $modelBranch->vendor_id;
            $model->email = $modelBranch->contact_email;
            $model->phone_number = $modelBranch->contact_number;
            $model->password = Hash::make($request->password);            
            $model->save();
            Common::log("Create","Branchuser has been saved",$model);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        return;
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
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($modelBranch, $request)
    {        
        DB::beginTransaction();
        try {
            $model = BranchUser::where('branch_id',$modelBranch->branch_id)->first();
            if($model !== null) { 
                $model->email = $modelBranch->contact_email;
                $model->phone_number = $modelBranch->contact_number;
                $model->vendor_id = $modelBranch->vendor_id;
                if($request->password !== null) {
                    $model->password = Hash::make($request->password);
                }
                $model->save();
            } else {  
                $model = new BranchUser();
                $model->branch_id = $modelBranch->branch_id;
                $model->vendor_id = $modelBranch->vendor_id;
                $model->email = $modelBranch->contact_email;
                $model->phone_number = $modelBranch->contact_number;                
                $model->password = Hash::make($request->password);
                $model->save();
            }
            DB::commit();
        }catch (\Throwable $e) {
            DB::rollback(); 
            throw $e;
        }
        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
