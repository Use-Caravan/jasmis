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
    VendorLang,
    BranchDeliveryArea,
    DeliveryArea,
    Order
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
                //'gender' => 'required|numeric|digits_between:1,2',
                //'dob' => 'required',
                'gender' => 'numeric|digits_between:1,2',
            ]);
            if($validator->fails()) {
                return $this->validateError($validator->errors());
            }
            $user = $user->find($userId);
            //print_r($user);exit;

            if( ( request()->phone_number !== $user->phone_number ) && $user->otp_verified == OTP_VERIFIED )  {
                $user->otp_verified = NULL;
                $user->otp_verified_at = NULL;
            }

            //print_r(request()->except('current_password','new_password','confirm_password'));exit;
            if( !empty(request()->dob) ) {
                $user = $user->fill(request()->except('current_password','new_password','confirm_password'));
                $user->dob = date('Y-m-d', strtotime(request()->dob));
            }
            else
                $user = $user->fill(request()->except('current_password','new_password','confirm_password','dob'));
            //print_r($user);exit;
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
            //print_r($userDetails);exit;
            if(request()->email !== $userDetails->email) {
                return $this->validateError(__('apimsg.Invalid user'));
            }

            $validator = Validator::make(request()->all(),[
                'phone_number' => "required|numeric|digits_between:8,15",
                'email' => "required|email",
                //'gender' => 'required|numeric|digits_between:1,2',
                'gender' => 'numeric|digits_between:1,2',
                'profile_picture' => 'nullable|image|mimes:jpeg,jpg,png|max:500',
                //'dob' => 'required',
            ]);
            if($validator->fails()) {
                return $this->validateError($validator->errors());
            }
            $user = $user->find($userId);            
            if($user->profile_image !== null && request()->hasFile('profile_image')) {
                FileHelper::deleteFile($user->profile_image,USER_PROFILE_IMAGE);                
            }
            //$user = $user->fill(request()->except('current_password','new_password','confirm_password'));
            if( !empty(request()->dob) ) {
                $user = $user->fill(request()->except('current_password','new_password','confirm_password'));
                $user->dob = date('Y-m-d', strtotime(request()->dob));
            }
            else
                $user = $user->fill(request()->except('current_password','new_password','confirm_password','dob'));
            
            if(request()->hasFile('profile_image')) {
                $user->profile_image = FileHelper::uploadFile(request()->profile_image,USER_PROFILE_IMAGE);                
            } 

            if( ( request()->phone_number !== $userDetails->phone_number ) && $userDetails->otp_verified == OTP_VERIFIED )  {
                $user->otp_verified = NULL;
                $user->otp_verified_at = NULL;
            }          

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

    public function wishlist($user_latitude=null,$user_longitude=null)
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
                    DB::raw("(SELECT COUNT(*) from branch_timeslot as BT where BT.branch_id = branch.branch_id and status = 1) as timeslotcount"),
                    DeliveryArea::tableName().'.zone_type'
                ])
                ->leftjoin(Vendor::tableName(),"$userWishlistTable.vendor_id",Vendor::tableName().".vendor_id")
                //->leftjoin($branchTable,"$userWishlistTable.branch_id","$branchTable.branch_id")
                ->leftjoin($branchTable,"$userWishlistTable.vendor_id","$branchTable.vendor_id")
                //->leftjoin($branchCuisineTable,"$userWishlistTable.branch_id","$branchCuisineTable.branch_id")
                ->leftjoin($branchCuisineTable,"$branchTable.branch_id","$branchCuisineTable.branch_id")
                ->leftjoin($cuisineTable,"$branchCuisineTable.cuisine_id","$cuisineTable.cuisine_id")
                //->leftjoin(BranchReview::tableName(),UserWishlist::tableName().".branch_id",BranchReview::tableName().".branch_id")
                ->leftjoin(BranchReview::tableName(),"$branchTable.branch_id",BranchReview::tableName().".branch_id")
                //->leftjoin(Vendor::tableName(),Branch::tableName().".vendor_id",Vendor::tableName().".vendor_id")
                ->leftJoin(BranchDeliveryArea::tableName(),BranchDeliveryArea::tableName().".branch_id","$branchTable.branch_id")
                ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
                ->where([
                    "$userWishlistTable.user_id" => $user->user_id,
                    "$userWishlistTable.status" => ITEM_ACTIVE,
                    "$cuisineTable.status" => ITEM_ACTIVE,
                ])                
                ->whereNull(Cuisine::tableName().".deleted_at")
                ->whereNull(Vendor::tableName().".deleted_at")
                ->whereNull(Branch::tableName().".deleted_at")
                //->groupBy("$userWishlistTable.branch_id");
                ->groupBy("$userWishlistTable.vendor_id");
                BranchLang::selectTranslation($query);
                CuisineLang::selectTranslation($query,'CUL');
                VendorLang::selectTranslation($query);
                $query = $query->havingRaw("timeslotcount > 0")->get();
                $data = UserWishlistResource::collection($query);

                $cnt = 0;
                /** Check whether the favorite restaurant within the user location **/
                foreach( $data as $whislist ) {
                    $vendor_id = $whislist->vendor_id;
                    $zone_type = $whislist->zone_type;

                    if( isset( $vendor_id ) && isset( $user_latitude ) && isset( $user_longitude ) ) {
                        $branchDeliveryArea = $this->checkDeliveryAreaAvailable( $vendor_id, $zone_type, $user_latitude, $user_longitude );

                        if($branchDeliveryArea === null || $branchDeliveryArea->count() == 0) {
                            unset($data[$cnt]);                        
                        }
                    }
                    $cnt++;
                }

                $this->setMessage(__('apimsg.wishlists are fetched'));
                return $this->asJson($data);
            break;
            
            case 'POST':                
                //$validator = Validator::make(request()->all(),['branch_key' => 'required']);
                $validator = Validator::make(request()->all(),['vendor_key' => 'required']);
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }
                DB::beginTransaction();
                try {
                    /*$branchId = Branch::findByKey(request()->branch_key);
                    if($branchId == null) {
                        return $this->validateError(__('apimsg.Invalid branch key'));
                    }*/
                    $vendorId = Vendor::findByKey(request()->vendor_key);
                    if($vendorId == null) {
                        return $this->validateError(__('apimsg.Invalid vendor key'));
                    }
                    $checkExists = UserWishlist::where([
                            'user_id' => $user->user_id,
                            'vendor_id' => $vendorId->vendor_id,
                        ])->first();
                
                    if($checkExists != null && $checkExists->status == ITEM_ACTIVE) {
                        return $this->commonError(__("apimsg.Vendor already exists in wishlist"));                
                    } elseif ($checkExists != null && $checkExists->status == ITEM_INACTIVE) {                        
                        $model = UserWishlist::find($checkExists->user_wishlist_id);
                        $model->status = ITEM_ACTIVE;
                        $model->save();
                    } else {                                
                        $model = new UserWishlist();
                        $model->user_id = $user->user_id;
                        $model->vendor_id = $vendorId->vendor_id;
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
                //$validator = Validator::make(request()->all(),['branch_key' => 'required']);
                $validator = Validator::make(request()->all(),['vendor_key' => 'required']);
                
                if($validator->fails()) {
                    return $this->validateError($validator->errors());
                }                               
                /*$branchId = Branch::findByKey(request()->branch_key);
                if($branchId == null) {
                    return $this->validateError(__('apimsg.Invalid branch key'));
                }*/

                $vendorId = Vendor::findByKey(request()->vendor_key);
                if($vendorId == null) {
                    return $this->validateError(__('apimsg.Invalid vendor key'));
                }
                
                $checkExists = UserWishlist::where([
                        'user_id' => $user->user_id,
                        'vendor_id' => $vendorId->vendor_id,
                    ])->first();   
                    
                if($checkExists != null && $checkExists->status == ITEM_INACTIVE) {
                    return $this->commonError(__("apimsg.Vendor already removed from wishlist"));
                }
                if ($checkExists === null) {
                    return $this->setMessage(__('apimsg.Vendor is unavailable to unwishlist'));
                }                
                
                DB::beginTransaction();
                try {
                    $userId = $user->user_id;    
                                                
                    $model = UserWishlist::where(['vendor_id' => $vendorId->vendor_id,'user_id' => $userId])->update(['status' => ITEM_INACTIVE]);
                    DB::commit();     
                    $this->setMessage(__('apimsg.Vendor has been removed from wishlist'));
                } catch (\Throwable $e) {
                    DB::rollback();
                    throw $e;
                }                
                return $this->asJson();
            break;
        }
    }

    public function checkDeliveryAreaAvailable( $vendor_id, $zone_type, $user_latitude, $user_longitude )
    {
        if($zone_type == DELIVERY_AREA_ZONE_CIRCLE) {
            
            $branchDeliveryArea = Branch::select([
                    'branch.branch_id',
                    'branch.branch_key',
                    'DA.*',
                    DB::raw("( 6371000 * acos( cos( radians($user_latitude) ) * cos( radians( DA.circle_latitude ) ) 
                    * cos( radians( DA.circle_longitude ) - radians($user_longitude) ) + sin( radians($user_latitude) )
                    * sin( radians( DA.circle_latitude ) ) ) ) as distance")
                ])
                ->leftJoin('branch_delivery_area as BDA','branch.branch_id','=','BDA.branch_id')
                ->leftJoin('delivery_area as DA','BDA.delivery_area_id','=','DA.delivery_area_id')
                ->where([
                    'branch.vendor_id' => $vendor_id,
                    'DA.status' => ITEM_ACTIVE,
                    "DA.zone_type" => DELIVERY_AREA_ZONE_CIRCLE,
                ])
                ->havingRaw("distance <  DA.zone_radius")
                ->orderBy('distance','ASC')
                ->first();
                
        }
        if($zone_type == DELIVERY_AREA_ZONE_POLYGON) {
            
            // $zoneLatLng = '[GEOMETRY - 129 B]';
            $branchDeliveryArea = BranchDeliveryArea::select([
                BranchDeliveryArea::tableName().".branch_id",
                DeliveryArea::tableName().".*"                
            ])
            ->leftJoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().".delivery_area_id",DeliveryArea::tableName().".delivery_area_id")
            ->leftJoin(Branch::tableName(),BranchDeliveryArea::tableName().".branch_id",Branch::tableName().".branch_id")
            ->where([
                DeliveryArea::tableName().".zone_type" => DELIVERY_AREA_ZONE_POLYGON,
                DeliveryArea::tableName().".status" => ITEM_ACTIVE,
                Branch::tableName().".vendor_id" => $vendor_id,
            ])
            ->whereNull(DeliveryArea::tableName().".deleted_at")
            ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(".$user_latitude.", ".$user_longitude."))")
            // ->whereRaw("ST_CONTAINS(".DeliveryArea::tableName().".zone_latlng, Point(1.122, 21.2121))")
            ->groupBy(BranchDeliveryArea::tableName().".branch_id")
            ->get();
            
        }
        /*print_r($branchDeliveryArea);exit;
        //print_r(array($branchDeliveryArea));exit;
        //echo count(array($branchDeliveryArea));exit;
        //echo count( is_countable( $branchDeliveryArea ) ? $branchDeliveryArea : [] );exit;

        //if($branchDeliveryArea === null || count(array($branchDeliveryArea)) == 0) {
        if($branchDeliveryArea === null || $branchDeliveryArea->count() == 0) {
        //if($branchDeliveryArea === null || count( is_countable( $branchDeliveryArea ) ? $branchDeliveryArea : [] ) == 0) {
            return ['status'=> false, 'error' => __('apimsg.The selected address in not within the delivery area of the branch')];
        }*/
        
        return $branchDeliveryArea;
    }

    public function ratings()
    {   
        $user = request()->user();
        //$branchId = Branch::findByKey(request()->branch_key);
        $orderId = Order::findByKey(request()->order_key);
        $validator = Validator::make(request()->all(),
            [
                //'branch_key' => 'required|exists:branch,branch_key',
                'order_key' => 'required|exists:order,order_key',
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
                    /*$checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'branch_id' => $branchId->branch_id,
                    ])->first();*/

                    $checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'order_id' => $orderId->order_id,
                    ])->first();

                    if($checkExists != null) {
                        return  $this->commonError(__("apimsg.It seems you have rated already!"));
                    } else { 
                        $model = new BranchReview();
                        $model->branch_id = $orderId->branch_id;
                        $model->vendor_id = $orderId->vendor_id;
                        $model->order_id = $orderId->order_id;
                        $model->fill(request()->except(['order_key'])); 
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
                    /*$checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'branch_id' => $branchId->branch_id,
                    ])->first();*/

                    $checkExists = BranchReview::where(['user_id' => $user->user_id,
                        'order_id' => $orderId->order_id,
                    ])->first();
                    
                    if($checkExists != null) {
                        $model = new BranchReview();
                        $model = $model->find($checkExists->branch_review_id);
                        $model->fill(request()->except(['order_key']));
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
