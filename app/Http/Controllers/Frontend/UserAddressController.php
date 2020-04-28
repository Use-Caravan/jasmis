<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Requests\Frontend\AddressRequest;
use App\Http\Controllers\Api\V1\UserAddressController as APIUserAddressController;
use App\{
    AddressType,
    UserAddress
};
use Auth;
use Common;

class UserAddressController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $address = (new APIUserAddressController)->index();
        $addressDetails = Common::getData($address);        
        $addressTypes = AddressType::getAddressType();
        return view('frontend.profile.address',['addressDetails' => $addressDetails, 'addressTypes' => $addressTypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        if(request()->ajax()) {
            $response = Common::compressData((new APIUserAddressController)->store());
            return response()->json($response);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $response = Common::compressData((new APIUserAddressController)->show($id));
        $response->data->action = route('address.update',$response->data->user_address_key);
        $response->data->method = 'PUT';
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {       
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
        $response = Common::compressData((new APIUserAddressController)->update($id));
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = Common::compressData((new APIUserAddressController)->destroy($id));
        return response()->json($response);
    }
}
