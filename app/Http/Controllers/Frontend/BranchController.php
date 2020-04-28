<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\Controller;
use App\Http\Requests\Frontend\SearchLocationRequest;
use App\Http\Controllers\Api\V1\BranchController as APIBranchController;
use App\Http\Controllers\Api\V1\ItemController as APIItemController;
use App\Http\Controllers\Api\V1\CartController as APICartController;
use App\Http\Controllers\Api\V1\OrderController as APIOrderController;
use App\{
    Banner,
    Cuisine,
    Item,
    Branch
};
use Common;
use Validator;
use Session;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {                  
        $branchList = (new APIBranchController)->index(); 
        $branchList = Common::getData($branchList); 
        $cuisineList = Cuisine::getList()->get()->toArray();
        $checkedCuisines = Cuisine::getCuisineNames($request->cuisine);
        $bannerImage = Banner::getBannerImage();

        $orderType =  $request->session()->put('order_type',$request->order_type);
        
        
        if($request->ajax()){            
            $branchs = view('frontend.branch.search-branch',compact('branchList'))->render();
            return response()->json(['list' => $branchs]);
        }
        return view('frontend.branch.branch-listing',compact('branchList','cuisineList','checkedCuisines','bannerImage'));
    }    


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function show($id, Request $request)
    {        
        if(session::has('corporate_voucher')) {                
            
            return redirect()->route('frontend.checkout',['branch_slug' => $id]);
        }
        $branch = Branch::where(['branch_slug' => $id])->first();        
        $branch_key = $branch->branch_key;        
        $request->session()->put('Branch-Key', $branch_key);
        
        $branchDetails = (new APIBranchController)->show($branch_key); 
        if(empty(Common::getData($branchDetails)) || Common::getData($branchDetails) === null) {
            return redirect()->route('frontend.index');
        }
        
        $branchDetails = Common::getData($branchDetails);
        $request->request->add(['branch_key' => $branch_key]);
        $itemDetails = Common::getData((new APIItemController)->index($branch_key));        
        $cartDetails = (new APICartController)->getCart();
        $cartDetails = Common::getData($cartDetails);            
        return view('frontend.branch.branch-detail',compact('branchDetails','itemDetails','cartDetails'));
    }

    public function nearBranchByVendor(Request $request)
    {  
        $branchList = (new APIBranchController)->branchByVendor();
        $branchList = Common::getData($branchList);
        if($request->ajax()){     
            $branchs = view('frontend.branch.nearby-branch',compact('branchList'))->render();
            return response()->json(['list' => $branchs]);
        }
        // return view('frontend.branch.branch-listing',compact('branchList'));
       
    }
       
}
