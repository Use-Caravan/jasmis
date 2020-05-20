<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\UserAddressResource
};
use App\Api\{
    UserAddress,
    AddressType,
    AddressTypeLang
};
use Validator;
use DB;
use Auth;

class UserAddressController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $addressType = new AddressType();
        $userAddress = new UserAddress();
        $query = UserAddress::getList()
        ->addSelect( 
            DB::raw("CONCAT_WS(', ',`address_line_one`,`address_line_two`,`landmark`,`company`) AS full_address")
            )
        ->leftJoin($addressType->getTable(),$userAddress->getTable().'.address_type_id',$addressType->getTable().'.address_type_id')
        ->where([
            $userAddress->getTable().'.status' => ITEM_ACTIVE,
            UserAddress::tableName().'.user_id' => request()->user()->user_id
        ]);
        AddressTypeLang::selectTranslation($query);
        $query = $query->orderBy('user_address_id','desc')->get();
        if(count($query) === 0) {
            $this->setMessage(__('apimsg.No Records Found'));
        } else {
            $this->setMessage(__('apimsg.User Address are fetched'));
            $data = UserAddressResource::collection($query);
            $this->setData($data);
        }                
        return $this->asJson();
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
     * Store a newly created resource in storage.UserAddress
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(),
            [
                'address_type_id' => 'required:exists:address_type,address_type_id',
                /* 'country_id' => "required",
                'city_id' => 'required',
                'area_id' => 'required', */
                'latitude' => 'required',
                'longitude' => 'required',
                // 'address_line_one' => 'required',
                /* 'address_line_two' => 'required',
                'landmark' => 'required',
                'company' => 'required'    */
            ]);        
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }      
        DB::beginTransaction();
        try {               
            $model = new UserAddress();
            $model->fill(request()->all()); 
            $model->user_id = request()->user()->user_id;
            $model->status = ITEM_ACTIVE;
            $model->save();
            DB::commit();     
            $this->setMessage(__('apimsg.Addrees has been saved.'));            
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        $data = new UserAddressResource($model);
        return $this->asJson($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = UserAddress::findByKey($id);        
        $data = new UserAddressResource($model);
        $this->setMessage(__("apimsg.User address has fetched"));
        return $this->asJson($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {    
        $model = UserAddress::findByKey($id);
        return $this->asJson($model);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {         
        $validator = Validator::make(request()->all(),
            [
                'address_type_id' => 'required:exists:address_type,address_type_id',
                /* 'country_id' => "required",
                'city_id' => 'required',
                'area_id' => 'required', */
                'latitude' => 'required',
                'longitude' => 'required',
                // 'address_line_one' => 'required',
                /* 'address_line_two' => 'required',
                'landmark' => 'required',
                'company' => 'required'    */
            ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        DB::beginTransaction();
        try {             
            $model = new UserAddress();
            $model = $model->findByKey($id);
            $model->fill(request()->all());
            $model->save();
            DB::commit();     
            $this->setMessage(__('apimsg.Addrees has been updated.'));            
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
        $data = new UserAddressResource($model);
        return $this->asJson($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = new UserAddress();
        $model = $model->findByKey($id);
        if($model == null) {
            return $this->validateError(__("apimsg.Invalid user address key"));
        }
        $model->delete();
        $data = new UserAddressResource($model);
        $this->setMessage(__('apimsg.User Address Has been deleted successfully'));
        return $this->asJson($data);
    }
}
