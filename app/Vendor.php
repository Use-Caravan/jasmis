<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use SMartins\PassportMultiauth\PassportMultiauth;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;
use App\{
    VendorLang,
    Branch,
    Country,
    City,
    Area,
    CountryLang,
    CategoryLang,
    CuisineLang,
    DeliveryAreaLang
    
};
use Common;
use DB;
use App;
use Auth;

class Vendor extends CAuthModel
{

    use Notifiable, HasMultiAuthApiTokens; /* HasApiTokens */ 
    /**
     * Enable the softdelte 
     *
     * @var class
     */
    use SoftDeletes;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'vendor';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'vendor_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'vendor_key';

    /**
     * The attributes that enable to generate unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['vendor_name','vendor_logo','vendor_description','vendor_address','confirm_password','restaurant_type','order_type','availability_status','approved_status','branch_id','csrf-token'];        



    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingleVendor($query)
    {
        if(APP_GUARD === GUARD_VENDOR) {            
            return $query->where(Vendor::tableName().'.vendor_id',Auth::guard(APP_GUARD)->user()->vendor_id);
        }
    }



    /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    }  

    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new VendorLang();
    }

    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{
        $self = new self();        
        $query = self::select($self->getTable().".*");
        VendorLang::selectTranslation($query);
        return $query;
	}
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }

    public static function getAll()
    {
        $self = new self();
        $area = new Area();
        $branch = new Branch();
        $query = self::getList()
        ->addSelect([$branch->getTable().'.branch_key',
        ])
        ->leftJoin($branch->getTable(),$self->getTable().'.vendor_id','=',$branch->getTable().'.vendor_id')
        ->leftJoin($area->getTable(),$self->getTable().'.area_id','=',$area->getTable().".area_id");
        AreaLang::selectTranslation($query);        
        return $query->groupBy($self->getTable().".vendor_id");
    }

    public static function getVendors()
    {
        $self = new self();
        $vendor_list = self::getList()->where([$self->getTable().'.status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($vendor_list,'vendor_name','vendor_id');
    }  

    
    /** this method not working have to analyse */
    public static function showVendorsDetails($vendorId)
    { 
        DB::enableQueryLog();

       $vendorTable = self::tableName();
       $query = self::getList()
            ->addSelect([
                    Branch::tableName().'.restaurant_type',
                    Branch::tableName().'.order_type',
                    Branch::tableName().'.availability_status',
                    Branch::tableName().'.approved_status', 
                    Branch::tableName().'.branch_id',
                    DB::raw("group_concat( DISTINCT( ".BranchCategory::tableName().".category_id) ) as category_ids"),
                    
                    DB::raw("(SELECT group_concat(category_name) FROM category_lang AS CL LEFT JOIN branch_category AS BC ON BC.category_id = CL.category_id WHERE branch_id = B.branch_id and language_code = '".App::getLocale()."') as product_stock"),

                    DB::raw("group_concat( ( CL.category_name) ) as category_name"),
                    //   DB::raw("group_concat( ( ".BranchCuisine::tableName().".cuisine_id) ) as cuisine_id"),
                    //   DB::raw("group_concat( ( CUL.cuisine_name) ) as cuisine_name"),
                    //   DB::raw("group_concat( ( ".BranchDeliveryArea::tableName().".delivery_area_id) ) as delivery_area_id"),
                    //   DB::raw("group_concat( ( DAL.delivery_area_name) ) as delivery_area_name"),
            ])
            ->leftjoin(Country::tableName(),"$vendorTable.country_id",Country::tableName().'.country_id')
            ->leftjoin(City::tableName(),"$vendorTable.city_id",City::tableName().'.city_id')
            ->leftjoin(Area::tableName(),"$vendorTable.area_id",Area::tableName().'.area_id')
            ->leftjoin(Branch::tableName(),"$vendorTable.vendor_id",Branch::tableName().'.vendor_id')
            ->leftjoin(BranchCategory::tableName(),Branch::tableName().'.branch_id',BranchCategory::tableName().'.branch_id')
            ->leftjoin(Category::tableName(),BranchCategory::tableName().'.category_id',Category::tableName().'.category_id')
            ->leftjoin(BranchCuisine::tableName(),Branch::tableName().'.branch_id',BranchCuisine::tableName().'.branch_id')
            ->leftjoin(Cuisine::tableName(),BranchCuisine::tableName().'.cuisine_id',Cuisine::tableName().'.cuisine_id')
            ->leftjoin(BranchDeliveryArea::tableName(),Branch::tableName().'.branch_id',BranchDeliveryArea::tableName().'.branch_id')
            ->leftjoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().'.delivery_area_id',DeliveryArea::tableName().'.delivery_area_id');
            CountryLang::selectTranslation($query,'CYL');
            CityLang::selectTranslation($query,'CTL');
            AreaLang::selectTranslation($query);
            BranchLang::selectTranslation($query);
            CategoryLang::selectTranslation($query);
            CuisineLang::selectTranslation($query,'CUL');
            DeliveryAreaLang::selectTranslation($query);
            $query->where(["$vendorTable.vendor_key" => $vendorId])
            ->groupBy("$vendorTable.vendor_id");   
            
            $data = $query->first();
//             print_r(
//     DB::getQueryLog()
// );
// exit;
        return $data;
    } 

    
    public static function showVendors($vendorId)
    { 
        DB::enableQueryLog();

       $vendorTable = self::tableName();
       $branchTable = Branch::tableName();
        $query = Vendor::getList()
             ->addSelect([
                    Branch::tableName().'.restaurant_type',
                    // Branch::tableName().'.order_type',
                    // Branch::tableName().'.availability_status',
                    //Branch::tableName().'.approved_status', 
                    Branch::tableName().'.branch_id',
                    //"$vendorTable.*",
                    DB::raw("group_concat( DISTINCT( ".BranchCategory::tableName().".category_id) ) as category_ids"),
                    // DB::raw("group_concat(DISTINCT ( CL.category_name) ) as category_name"),
                    DB::raw("(SELECT group_concat(category_name) FROM category_lang AS CL LEFT JOIN branch_category AS BC ON BC.category_id = CL.category_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as category_names"),
                    DB::raw("group_concat(DISTINCT ( ".BranchCuisine::tableName().".cuisine_id) ) as cuisine_id"),
                    DB::raw("(SELECT group_concat(cuisine_name) FROM cuisine_lang AS CL LEFT JOIN branch_cuisine AS BC ON BC.cuisine_id = CL.cuisine_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as cuisine_names"),
                    DB::raw("group_concat( DISTINCT( ".BranchDeliveryArea::tableName().".delivery_area_id) ) as delivery_area_id"),
                    DB::raw("(SELECT group_concat(delivery_area_name) FROM delivery_area_lang AS DL LEFT JOIN branch_delivery_area AS BD ON DL.delivery_area_id = BD.delivery_area_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as delivery_area_names"),
                    
            ])
             //->leftjoin($vendorTable,"$branchTable.vendor_id","$vendorTable.vendor_id")
            ->leftjoin(Country::tableName(),"$vendorTable.country_id",Country::tableName().'.country_id')
            ->leftjoin(City::tableName(),"$vendorTable.city_id",City::tableName().'.city_id')
            ->leftjoin(Area::tableName(),"$vendorTable.area_id",Area::tableName().'.area_id')
            ->leftjoin($branchTable,"$vendorTable.vendor_id",$branchTable.'.vendor_id')
            ->leftjoin(BranchCategory::tableName(),"$branchTable.branch_id",BranchCategory::tableName().'.branch_id')
            ->leftjoin(Category::tableName(),BranchCategory::tableName().'.category_id',Category::tableName().'.category_id')
            ->leftjoin(BranchCuisine::tableName(),Branch::tableName().'.branch_id',BranchCuisine::tableName().'.branch_id')
            ->leftjoin(Cuisine::tableName(),BranchCuisine::tableName().'.cuisine_id',Cuisine::tableName().'.cuisine_id')
            ->leftjoin(BranchDeliveryArea::tableName(),Branch::tableName().'.branch_id',BranchDeliveryArea::tableName().'.branch_id')
            ->leftjoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().'.delivery_area_id',DeliveryArea::tableName().'.delivery_area_id');
            CountryLang::selectTranslation($query,'CYL');
            CityLang::selectTranslation($query,'CTL');
            AreaLang::selectTranslation($query);
            //VendorLang::selectTranslation($query);
            BranchLang::selectTranslation($query);
            CategoryLang::selectTranslation($query);
            CuisineLang::selectTranslation($query,'CUL');
            DeliveryAreaLang::selectTranslation($query);
            $query->where(["$vendorTable.vendor_key" => $vendorId])
            ->groupBy("$vendorTable.vendor_id");   
            $data = $query->first();
            return $data;
    } 
    
    public static function restaurantTypes($restaurantType = null)
    {
        $options = [
            RESTAURANT_TYPE_VEG      => __('admincrud.Veg'),
            RESTAURANT_TYPE_NON_VEG  => __('admincrud.Non Veg'),
            RESTAURANT_TYPE_BOTH     => __('admincrud.Both')
        ];
        return ($restaurantType !== null && isset($options[$restaurantType])) ? $options[$restaurantType] : $options;
    }

    public function commissionTypes($commissionType = null)
    {
        $options = [
            VENDOR_COMMISSION_TYPE_PERCENTAGE      => __('admincrud.Percentage'),
            VENDOR_COMMISSION_TYPE_AMOUNT  => __('admincrud.Amount'),            
        ];
        return ($commissionType !== null && isset($options[$commissionType])) ? $options[$commissionType] : $options;
    }    

    public function availablityTypes($availablityType = null)
    {
        $options = [
            AVAILABILITY_STATUS_OPEN      => __('admincrud.Open'),
            AVAILABILITY_STATUS_CLOSED  => __('admincrud.Closed'),
            AVAILABILITY_STATUS_BUSY     => __('admincrud.Busy'),
            AVAILABILITY_STATUS_OUT_OF_SERVICE     => __('admincrud.Out Of Service')
        ];
        return ($availablityType !== null && isset($options[$availablityType])) ? $options[$availablityType] : $options;
    }

    public function approvedStatus($approvedStatus = null)
    {          
        $options = [
            BRANCH_APPROVED_STATUS_PENDING      => __('admincrud.Pending'),
            BRANCH_APPROVED_STATUS_APPROVED  => __('admincrud.Approved'),
            BRANCH_APPROVED_STATUS_REJECTED     => __('admincrud.Rejected')
        ];                
        return ($approvedStatus !== null && isset($options[$approvedStatus])) ? $options[$approvedStatus] : $options;
    }

    public static function vendorPaymentTimeslot($year = null)
    {
        if($year === null) {
            $year = date('Y');
        }
        $startDate = date($year.'-01-01');
        if($year == date('Y')) {
            $endDate = date($year.'-m-d');
        } else {
            $endDate = date($year.'-12-31');
        }
                
        $startDate = strtotime($startDate);
        $endDate   =  strtotime($endDate);

        if( $endDate > $startDate ) {
            while( $endDate >= $startDate ) {
                $fromDate = date( 'Y-m-d', $startDate );
                $startDate = strtotime( ' +7 day ', $startDate );
                $endingDate = date('Y-m-d',$startDate);
                $startDate = strtotime( ' +1 day ', $startDate);  
                $dates["$fromDate / $endingDate"] = "$fromDate - $endingDate";
            }            
        }
        return array_reverse($dates, true);        
    }    
}

