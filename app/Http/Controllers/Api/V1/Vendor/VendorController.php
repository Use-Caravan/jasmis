<?php
namespace App\Http\Controllers\Api\V1\Vendor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Api\V1\Vendor\VendorResource;
use App\Http\Resources\Api\V1\Vendor\BranchResource;
use App\Http\{
        Controllers\Api\V1\Controller,
        Requests\Admin\VendorRequest   
    };
use App\{
        Mail\VendorEmail,
        Api\Area,
        Api\Branch,
        Api\BranchUser,
        Api\BranchCategory,
        Api\BranchCuisine,
        Api\BranchDeliveryArea,
        Api\BranchLang,
        Api\Category,
        Api\City,
        Api\Country,
        Api\Cuisine,
        Api\Order,
        Api\Vendor,
        Api\VendorLang,
        Api\DeliveryArea,
        Vendor as CommonVendor
    };
use Common;
use Validator;
use DB;

/**
 * @Title("Vendor Management")
 */
class VendorController extends Controller
{
    public function profile()
    {
        $user = request()->user();
        if(request()->method() == 'GET') {
            $user->access_token = request()->bearerToken();                
            $this->setMessage(__("apimsg.Vendor details are fetched"));
            $this->setData(new VendorResource($user));
            return $this->asJson(); 

        } elseif (request()->method() == 'PUT') { 
            
            if($user instanceof CommonVendor) {
                $vendor = new Vendor();
                $vendorTable = $user->getTable();
                $vendorKey = request()->user()->vendor_key;
                $vendorId = request()->user()->vendor_id;
                $validator = Validator::make(request()->all(),[
                    'mobile_number' => "required|numeric|digits_between:8,15|unique:$vendorTable,mobile_number,$vendorKey,vendor_key",
                    'email' => "required|email|unique:$vendorTable,email,$vendorKey,vendor_key",
                    'username' => 'required',
                ]);
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }
                $vendor = Vendor::find($vendorId);
                $vendor = $vendor->fill(request()->all());
                $vendor->save();
                $this->setMessage(__("apimsg.Vendor details are updated"));
                $this->setData(new VendorResource($vendor));
            } else {
                $branchUser = new BranchUser();
                $vendorTable = $user->getTable();
                $branchUserKey = request()->user()->branch_user_key;
                $branch = Branch::find(request()->user()->branch_id);
                $branchKey = $branch->branch_key;
                $branchId = request()->user()->branch_id;
                $validator = Validator::make(request()->all(),[
                    'mobile_number' => "required|numeric|digits_between:8,15|unique:branch_user,phone_number,$branchUserKey,branch_user_key|unique:branch,contact_number,$branchKey,branch_key",
                    'email' => "required|email|unique:branch_user,email,$branchUserKey,branch_user_key|unique:branch,contact_email,$branchKey,branch_key",
                    'username' => 'required',
                ]);
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }
                $fillable = [
                    'contact_email' => request()->email,
                    'contact_number' => request()->phone_number
                ];
                $branch = $branch->fill($fillable);
                $branch->save();
                $branchUser = BranchUser::findByKey($branchUserKey);
                $branchUser = $branchUser->fill(request()->all());
                $branchUser->save();
                $this->setMessage(__("apimsg.Branch details are updated"));
                $this->setData(new VendorResource($branchUser));
            }
            return $this->asJson();
        }
    }
    public function changeBranchStatus()
    {       
        if(request()->user() instanceof CommonVendor) {
            return $this->commonError(__("apimsg.You have loggedin as vendor. So you can't change the status"));
        } else {
            $branch = Branch::find(request()->user()->branch_id);
            $branch->availability_status = request()->availability_status;
            $branch->save();
            $this->setMessage(__("apimsg.Branch status updated successfully"));
            return $this->asJson();
        }
    }

    public function BranchStatus()
    {
        if(request()->user() instanceof CommonVendor) {
            return $this->commonError(__("apimsg.You have loggedin as vendor. So you can't get the status"));
        } else {
            $branch = Branch::find(request()->user()->branch_id);
            $this->setMessage(__("apimsg.Branch availability status are fetched"));
            $this->setData(new BranchResource($branch));
            return $this->asJson();
        }
    }
}
