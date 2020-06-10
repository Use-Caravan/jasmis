<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\{
    Controllers\Api\V1\Controller,
    Resources\Api\V1\BranchResource,
    Resources\Api\V1\CuisineResource,
    Resources\Api\V1\BranchInfoResource    
};
use App\Api\{ 
    Branch,
    BranchTimeslot,
    BranchCuisine,
    Cuisine,
    CuisineLang,
    BranchCategory,
    Category,
    DeliveryArea,
    BranchDeliveryArea,
    BranchLang,
    BranchReview,
    User,
    Vendor,
    Country,
    City,
    Area,
    VendorLang,
    AreaLang,
    CountryLang,
    Order,
    CityLang
};
use Validator;
use DB;
use App;
use Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {                  
        $branch = new Branch();
        $cuisine = new Cuisine();
        $branchCuisine = new BranchCuisine();        
        $branchCuisineTable = $branchCuisine->getTable();                
        $branches = $branch::getBranches()->get();
        return $this->asJson($branches);        
        $branches = BranchResource::collection($branches);
        $cuisineList = $cuisine::getList()->where([$cuisine->getTable().'.status' => ITEM_ACTIVE])->get();        
        $cuisineList = CuisineResource::collection($cuisineList);            
        $query = ['branches' => $branches,'cuisines' => $cuisineList];
        $this->setMessage( __('apimsg.Branches are fetched') );
        return $this->asJson($query);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $branchInfo = Branch::getBranches()->first();
        $this->setMessage( __('apimsg.Branch Informations And Ratings are fetched') );
        if($branchInfo === null) {
            return $this->asJson([]);
        }
        return $this->asJson(new BranchResource($branchInfo));
    }

    public function branchTimeslot()
    {   
        $validator = Validator::make(request()->all(),[
            'branch_key' => 'required|exists:'.Branch::tableName().',branch_key',
            'order_type' => 'required',
        ]);
        if($validator->fails()) {
            return $this->validateError($validator->errors());
        }
        $branch = Branch::findByKey(request()->branch_key);
        $startDate = strtotime( date('Y-m-d H:i:s') );
        $endDate = strtotime( date('Y-m-d H:i:s', strtotime("+2 days")) );
        $day = [];
        $count = 0;
        for ( $i = $startDate; $i <= $endDate; $i = $i + 86400 ) {
            $thisDate = date( 'Y-m-d', $i ); // 2010-05-01, 2010-05-02, etc
            $dayNumber = date("N", strtotime($thisDate));
                        
            $timeslots = BranchTimeslot::where([
                    'branch_id' => $branch->branch_id, 
                    'timeslot_type' => request()->order_type,
                    'day_no' => $dayNumber,
                    'status' => ITEM_ACTIVE
                ])->limit(7)->orderBy('day_no','Asc')->first();
            if($timeslots !== null) {
                $startTime = strtotime(date("H:i:s", strtotime($timeslots->start_time . "+30 minutes")));
                                
                if(strtotime($thisDate) == strtotime( date('Y-m-d')) && $startTime <= strtotime(date('H:i:s'))) {
                    $startTime = strtotime(date('H:i:s'));
                }
                $endTime = strtotime($timeslots->end_time);
                
                // echo date('H:i:s',$startTime)." ".date('H:i:s',$endTime);
                // exit;

                $day[$count]['date'] = $thisDate;
                $day[$count]['times'] = [];
                for ( $t = $startTime; $t <= $endTime; $t = $t + 1800 ) {
                    $day[$count]['times'][] = date( 'h:i a', $t );
                }
                $count++;
            }
        }
        $days = [];
        $count = 0;
        foreach($day as $key => $value) {
            if(!empty($value['times'])) {
                $days[$count]['date'] = $value['date'];
                $days[$count]['times'] = $value['times'];
                $count++;
            }            
        }

        /** This is for mobile team * in required is null data will send to mobile team */
        if(request()->request_from === null) {            
            $this->setMessage(__('apimsg.Time slot has feteched'));
            return $this->asJson($days);
        } else { /** This is for web frontend  */
                        
            $branchDetails = Branch::findByKey(request()->branch_key);
            if($branchDetails === null) {
                return $this->commonError(__('apimsg.Branch key is not found'));
            }                
            if(request()->order_type !== null) {
                if(request()->order_type != $branchDetails->order_type && $branchDetails->order_type != ORDER_TYPE_BOTH) {
                    return $this->commonError( __('apimsg.This delivery type is not available for this branch') );
                }
            }
            
            /** Check the Order Time is available or not */   
            $delivery_date = date('Y-m-d');
            $delivery_time = date('H:i:s');

            $dayNumber = date("N", strtotime($delivery_date));            
            $timeSlot = BranchTimeslot::select('*')            
            ->where([
                'day_no' => $dayNumber,
                'status' => ITEM_ACTIVE,
                'timeslot_type' => request()->order_type,
                'branch_id' => $branchDetails->branch_id,
            ])
            ->whereTime('start_time', '<', $delivery_time)
            ->whereTime('end_time', '>', $delivery_time)
            ->first();
            if($timeSlot === null) {
                $delivery_type = 1;
            } else {
                $delivery_type = 2;
            }
            $deliveryTypes = (new Order())->deliveryTypes();
            $view = view('frontend.layouts.partials.delivery-slots',compact('delivery_type','days'))->render();
            return $this->asJson($view);
        }
    }    

    public function branchByVendor($vendor_key=null,$branch_key=null)
    {           
        $branches = Branch::getBranches()->get();
        // return $this->asJson($branches);
        $branches = BranchResource::collection($branches);

        // return $this->asJson($branches);
        //$query = ['branches' => $branches];
        $this->setMessage( __('apimsg.Branches are fetched') );
        return $this->asJson($branches);
    }
     
}
