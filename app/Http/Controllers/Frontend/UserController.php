<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Controllers\Api\V1\UserController as APIUserController;
use App\User;
use Auth;
use Hash;
use Common;

class UserController extends Controller
{    
    public function profileUpdate(Request $request)
    {   
        if($request->ajax()){            
            if($request->current_password !== null) {
                $response = Common::compressData((new APIUserController)->changePassword());
            }
            request()->request->add(['dob'=> date('Y-m-d',strtotime($request->dob) )]);            
            $response = Common::compressData((new APIUserController)->userDetails());
            return response()->json($response);
        }
    }
    
    public function wishlist(Request $request)
    {
        switch(request()->method()) {
            case 'GET';
                $wishList = (new APIUserController)->wishlist();
                $wishListDetails = Common::getData($wishList);
                return view('frontend.profile.favourite',compact('wishListDetails')); 
            break;
            case 'POST';
                if($request->ajax()){
                    $wishList = (new APIUserController)->wishlist();
                    $response = Common::compressData($wishList);
                    return response()->json($response); 
                } 
            break;               
            case 'PUT';
                if($request->ajax()){
                    $wishList = (new APIUserController)->wishlist();
                    $response = Common::compressData($wishList);
                    return response()->json($response); 
                }
            break;            
        }
    }

    public function ratings(Request $request)
    {   
        if($request->ajax()){
            $ratings = (new APIUserController)->ratings();
            $response = Common::compressData($ratings);
            return response()->json($response); 
        }
    }
}
