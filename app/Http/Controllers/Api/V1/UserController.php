<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\UserWishlistResource,
    Resources\Api\V1\BranchRatingResource,
    Resources\Api\V1\UserResource
};
use App\Api\{
    User,
    UserWishlist,
    Vendor,
    Branch,
    BranchLang,
    BranchCuisine,
    BranchReview,
    Cuisine,
    CuisineLang,
    VendorLang
};
use Auth;
use FileHelper;
use DB;
use Validator;
use Hash;
use App\User as AppUser;

class UserController extends Controller
{
    public function userDetails()
    {
        $user = request()->user();        
        if(request()->method() == 'GET') {
            $user->access_token = request()->bearerToken();    
            $this->setMessage(__("apimsg.User details are fetched"));
            $this->setData(new UserResource($user));
            return $this->asJson(); 

        } elseif (request()->method() == 'PUT') { 
            $user = new User();
            $userTable = $user->getTable();
            $userKey = request()->user()->user_key;
            $userId = request()->user()->user_id;            
            $validator = Validator::make(request()->all(),[
                'phone_number' => "required|numeric|digits_between:8,15|unique:$userTable,phone_number,$userKey,user_key,deleted_at,NULL",
                //'phone_number' => "required|numeric|digits_between:8,15|unique:$userTable,phone_number,$userKey,user_key",
                'email' => "required|email",
                'gender' => 'required|numeric|digits_between:1,2',
                'dob' => 'required',
            ]);
            if($validator->fails()) {
                return $this->validateError($validator->errors());
            }
            $user = $user->find($userId);
            $user = $user->fill(request()->except('current_password','new_password','confirm_password'));
            $user->save();
            $this->setMessage(__("apimsg.User details are updated"));
            $this->setData(new UserResource($user));
            return $this->asJson();
        } else if (request()->method() == 'POST') {  
            $user = new User();
            $userTable = $user->getTable();
            $userKey = request()->user()->user_key;
            $userId = request()->user()->user_id; 
            $userDetails = User::findByKey($userKey);
            if(request()->email !== $userDetails->email) {
                return $this->validateError(__('apimsg.Invalid user'));
            }
            $validator = Validator::make(request()->all(),[
                'phone_number' => "required|numeric|digits_between:8,15",
                'email' => "required|email",
                'gender' => 'required|numeric|digits_between:1,2',
                'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:500',
                'dob' => 'required',
            ]);
            if($validator->fails()) {
                return $this->validateError($validator->errors());
            }
            $user = $user->find($userId);            
            if($user->profile_image !== null && request()->hasFile('profile_image')) {
                FileHelper::deleteFile($user->profile_image,USER_PROFILE_IMAGE);                
            }
            $user = $user->fill(request()->except('current_password','new_password','confirm_password'));
            if(request()->hasFile('profile_image')) {
                $user->profile_image = FileHelper::uploadFile(request()->profile_image,USER_PROFILE_IMAGE);                
            }            
            $user->dob = date('Y-m-d', strtotime(request()->dob));
            $user->save();
            $this->setMessage(__("apimsg.User details are updated"));
            $this->setData(new UserResource($user));
            return $this->asJson();
        }
    }

    public function profileImage(Request $request) 
    {   
        $userId = auth()->guard(GUARD_USER_API)->user()->user_id;
        $validator = Validator::make($request->all(),
            [
                'profile_image'  => 'required|image|mimes:jpeg,jpg,png|max:500',
            ]);
        if($validator->fails()) {            
            return $this->validateError($validator->errors());
        }           
        $user = User::find($userId);
        if($user->profile_image !== null && $request->hasFile('profile_image')) {
            FileHelper::deleteFile($user->profile_image,USER_PROFILE_IMAGE);
        }
        $user->profile_image = FileHelper::uploadFile($request->profile_image,USER_PROFILE_IMAGE);
        $user->save();
        $this->setData(new UserResource($user));
        $this->setMessage( __('apimsg.Profile image updated') );
        return $this->asJson();
    }

    public function changePassword()
    {        
        $user = User::find(request()->user()->user_id);        
        $validator = Validator::make(request()->all(),[
            'current_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        
        ]);
        if($validator->fails()) {            
            return $this->validateError($validator->errors());
        }
        if (!Hash::check(request()->current_password, $user->password)) {
            return $this->commonError(__('apimsg.Current password is mismatch'));
        }
        $user->password = Hash::make(request()->new_password);
        $user->save();
        $this->setData(new UserResource($user));
        $this->setMessage(__("apimsg.Password changed successfully"));
        return $this->asJson();
    }

    public function wishlist()
    {   
        $user = request()->user();
        $branch = new Branch();
        $branchTable = $branch->getTable();
        $userWishlist = new UserWishlist();
        $userWishlistTable = $userWishlist->getTable();
        $branchCuisine = new BranchCuisine();
        $branchCuisineTable = $branchCuisine->getTable();
        $branchReview = new BranchReview();
        $branchReviewTable = $branchReview->getTable(); 
        $cuisine = new Cuisine();
        $cuisineTable = $cuisine->getTable();
       

        switch(request()->method()) {
            case 'GET';
                $query = $userWishlist->getList()
                ->select([
                    "$userWishlistTable.branch_id",
                    "$branchTable.*",
                    "$cuisineTable.*",
                    Vendor::tableName().".color_code",
                    Vendor::tableName().".vendor_key",
                    "$branchCuisineTable.cuisine_id",
                    DB::raw('GROUP_CONCAT( DISTINCT(CUL.cuisine_name)) as cuisines'),
                    DB::raw("avg(".BranchReview::tableName().".rating) as branch_avg_rating"),
                    DB::raw("(SELECT COUNT(*) from branch_timeslot as BT where BT.branch_id = branch.branch_id and status = 1) as timeslotcount")
                ])
                ->leftjoin($branchTable,"$userWishlistTable.branch_id","$branchTable.branch_id")
                ->leftjoin($branchCuisineTable,"$userWishlistTable.branch_id","$branchCuisineTable.branch_id")
                ->leftjoin($cuisineTable,"$branchCuisineTable.cuisine_id","$cuisineTable.cuisine_id")
                ->leftjoin(BranchReview::tableName(),UserWishlist::tableName().".branch_id",BranchReview::tableName().".branch_id")
                ->leftjoin(Vendor::tableName(),Branch::tableName().".vendor_id",Vendor::tableName().".vendor_id")
                ->where([
                    "$userWishlistTable.user_id" => $user->user_id,
                    "$userWishlistTable.status" => ITEM_ACTIVE,
                    "$cuisineTable.status" => ITEM_ACTIVE,
                ])                
                ->whereNull(Cuisine::tableName().".deleted_at")
                ->whereNull(Vendor::tableName().".deleted_at")
                ->whereNull(Branch::tableName().".deleted_at")
                ->groupBy("$userWishlistTable.branch_id");
                BranchLang::selectTranslation($query);
                CuisineLang::selectTranslation($query,'CUL');
                VendorLang::selectTranslation($query);
                $query = $query->havingRaw("timeslotcount > 0")->get();
                $data = UserWishlistResource::collection($query);
                $this->setMessage(__('apimsg.wishlists are fetched'));
                return $this->asJson($data);
            break;
            
            case 'POST':                
                $validator = Validator::make(request()->all(),['branch_key' => 'required']);
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }
                DB::beginTransaction();
                try {
                    $branchId = Branch::findByKey(request()->branch_key);
                    if($branchId == null) {
                        return $this->validateError(__('apimsg.Invalid branch key'));
                    }
                    $checkExists = UserWishlist::where([
                            'user_id' => $user->user_id,
                            'branch_id' => $branchId->branch_id,
                        ])->first();
                
                    if($checkExists != null && $checkExists->status == ITEM_ACTIVE) {
                        return $this->commonError(__("apimsg.Branch already exists in wishlist"));                
                    } elseif ($checkExists != null && $checkExists->status == ITEM_INACTIVE) {                        
                        $model = UserWishlist::find($checkExists->user_wishlist_id);
                        $model->status = ITEM_ACTIVE;
                        $model->save();
                    } else {                                
                        $model = new UserWishlist();
                        $model->user_id = $user->user_id;
                        $model->branch_id = $branchId->branch_id;
                        $model->status = ITEM_ACTIVE;
                        $model->save();                        
                    }
                    DB::commit();
                    $this->setMessage(__('apimsg.Restaurant has been added to wishlist'));
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                }                
                return $this->asJson($model);
            break;   

            case 'PUT': 
                $validator = Validator::make(request()->all(),['branch_key' => 'required']);
                
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }                               
                $branchId = Branch::findByKey(request()->branch_key);
                if($branchId == null) {
                    return $this->validateError(__('apimsg.Invalid branch key'));
                }
                
                $checkExists = UserWishlist::where([
                        'user_id' => $user->user_id,
                        'branch_id' => $branchId->branch_id,
                    ])->first();   
                    
                if($checkExists != null && $checkExists->status == ITEM_INACTIVE) {
                    return $this->commonError(__("apimsg.Branch already removed from wishlist"));
                }
                if ($checkExists === null) {
                    return $this->setMessage(__('apimsg.Branch is unavailable to unwishlist'));
                }                
                
                DB::beginTransaction();
                try {
                    $userId = $user->user_id;    
                                                
                    $model = UserWishlist::where(['branch_id' => $branchId->branch_id,'user_id' => $userId])->update(['status' => ITEM_INACTIVE]);
                    DB::commit();     
                    $this->setMessage(__('apimsg.Restaurant has been removed from wishlist'));
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                }                
                return $this->asJson();
            break;
        }
    }

    public function ratings()
    {   
        $user = request()->user();
        $branchId = Branch::findByKey(request()->branch_key);
        $validator = Validator::make(request()->all(),
            [
                'branch_key' => 'required|exists:branch,branch_key',
                'rating' => 'required',
                //'review' => 'required',
            ],
        $messages = 
            [
                'rating.required' => __('apimsg.Please Enter Your Rating'),
                //'review.required' => 'Please Enter Your Comments.',
            ]
        );
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        switch(request()->method()) {
            case 'POST':      
                DB::beginTransaction();
                try { 
                    $checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'branch_id' => $branchId->branch_id,
                    ])->first();
                    if($checkExists != null) {
                        return  $this->commonError(__("apimsg.It seems you have rated already!"));
                    } else { 
                        $model = new BranchReview();
                        $model->branch_id = $branchId->branch_id;
                        $model->vendor_id = $branchId->vendor_id;
                        $model->fill(request()->all()); 
                        $model->user_id = $user->user_id;
                        $model->status = ITEM_ACTIVE;                        
                        $model->save();
                    }
                    DB::commit();
                    $this->setMessage(__('apimsg.Rating has been saved'));            
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                }                
                return $this->asJson();
            break;
            case 'PUT';
            
                DB::beginTransaction();
                try { 
                    $checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'branch_id' => $branchId->branch_id,
                    ])->first();
                    if($checkExists != null) {
                        $model = new BranchReview();
                        $model = $model->find($checkExists->branch_review_id);
                        $model->fill(request()->all());
                        $model->save();
                    }
                    else { 
                        return  $this->commonError(__("apimsg.It seems you have rated already!"));
                    }
                    DB::commit();     
                    $this->setMessage(__('apimsg.Rating has been updated.'));            
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                }
                return $this->asJson();
            break;
        }
    }
}
