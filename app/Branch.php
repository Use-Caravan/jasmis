<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{
    Area,
    AreaLang,
    BranchLang,
    Vendor,
    VendorLang,
    Scopes\VendorScope,
    Scopes\BranchScope
};
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;
use Auth;

class Branch extends CModel
{
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
	protected $table = 'branch';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'branch_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'branch_key';

    /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['branch_name','branch_logo','branch_address','delivery_area_id','cuisine_id','category_id','password','confirm_password','csrf-token'];    


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope());
        static::addGlobalScope(new BranchScope());
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
	 *
	 * @var query
	 */
    public static function getList()
	{
        $self  = new self();
        $query = self::select($self->getTable().".*");
        BranchLang::selectTranslation($query);
        return $query;
    }

    /**
	 *
	 * @var query
	 */
	public static function findByVendorId($vendorId)
	{
		return self::where('vendor_id', $vendorId)->first();
    }

    /**
     * @return object find by key
     */
    public static function findByKey($branchKey)
    {
        return self::where(self::uniqueKey(), $branchKey)->first();
    }
    
    public static function searchListing($search) 
    {
        $listing=Branch::getList()
                ->where('branch_name','LIKE','%'.$search."%")->get();
        return $listing; 
    }   

    public static function getAll()
    {
        $self = new self();
        $vendor = new Vendor();
        $area = new Area();
        $query = self::getList()
        ->addSelect(self::tableName().'.*')
        ->leftJoin($vendor->getTable(),$self->getTable().'.vendor_id','=',$vendor->getTable().".vendor_id")
        ->leftJoin($area->getTable(),$self->getTable().'.area_id','=',$area->getTable().".area_id");
        AreaLang::selectTranslation($query);
        VendorLang::selectTranslation($query);
        // return $query->groupBy($self->getTable().".branch_id");
        return $query; 
    }

    public static function showBranch($branchKey)
    {
        $query = self::getList()
            ->addSelect([
                DB::raw("group_concat( DISTINCT( ".BranchCategory::tableName().".category_id) ) as category_ids"),
                // DB::raw("group_concat(DISTINCT ( CL.category_name) ) as category_name"),
                DB::raw("(SELECT group_concat(category_name) FROM category_lang AS CL LEFT JOIN branch_category AS BC ON BC.category_id = CL.category_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as category_names"),
                DB::raw("group_concat(DISTINCT ( ".BranchCuisine::tableName().".cuisine_id) ) as cuisine_id"),
                DB::raw("(SELECT group_concat(cuisine_name) FROM cuisine_lang AS CL LEFT JOIN branch_cuisine AS BC ON BC.cuisine_id = CL.cuisine_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as cuisine_names"),
                DB::raw("group_concat( DISTINCT( ".BranchDeliveryArea::tableName().".delivery_area_id) ) as delivery_area_id"),
                DB::raw("(SELECT group_concat(delivery_area_name) FROM delivery_area_lang AS DL LEFT JOIN branch_delivery_area AS BD ON DL.delivery_area_id = BD.delivery_area_id WHERE branch_id = branch.branch_id and language_code = '".App::getLocale()."') as delivery_area_names"),
            ])
            ->leftjoin(Country::tableName(),Branch::tableName().".country_id",Country::tableName().'.country_id')
            ->leftjoin(City::tableName(),Branch::tableName().".city_id",City::tableName().'.city_id')
            ->leftjoin(Area::tableName(),Branch::tableName().".area_id",Area::tableName().'.area_id')
            ->leftjoin(Vendor::tableName(),Branch::tableName().".vendor_id",Vendor::tableName().'.vendor_id')
            ->leftjoin(BranchCategory::tableName(),Branch::tableName().'.branch_id',BranchCategory::tableName().'.branch_id')
            ->leftjoin(Category::tableName(),BranchCategory::tableName().'.category_id',Category::tableName().'.category_id')
            ->leftjoin(BranchCuisine::tableName(),Branch::tableName().'.branch_id',BranchCuisine::tableName().'.branch_id')
            ->leftjoin(Cuisine::tableName(),BranchCuisine::tableName().'.cuisine_id',Cuisine::tableName().'.cuisine_id')
            ->leftjoin(BranchDeliveryArea::tableName(),Branch::tableName().'.branch_id',BranchDeliveryArea::tableName().'.branch_id')
            ->leftjoin(DeliveryArea::tableName(),BranchDeliveryArea::tableName().'.delivery_area_id',DeliveryArea::tableName().'.delivery_area_id');
            CountryLang::selectTranslation($query,'CYL');
            CityLang::selectTranslation($query,'CTL');
            AreaLang::selectTranslation($query);
            VendorLang::selectTranslation($query);
            CategoryLang::selectTranslation($query);
            CuisineLang::selectTranslation($query,'CUL'); 
            DeliveryAreaLang::selectTranslation($query);
            $query->where([Branch::tableName().'.branch_key' => $branchKey])
            ->groupBy(Branch::tableName().'.branch_id');   
            $data = $query->first();
            return $data;
    }

    public static function getBranch($vendorId = null)
    { 
        $self = new self();
        $query = self::getList();
        if($vendorId != null) {
            $query = $query->where([self::tableName().'.vendor_id' => $vendorId]);
        }
        $query = $query->where([self::tableName().'.status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($query,'branch_name','branch_id');
                
    }

    public function availablityStatus($availablityStatus = null)
    {
        $options = [
            AVAILABILITY_STATUS_OPEN      => __('admincrud.Open'),
            AVAILABILITY_STATUS_CLOSED  => __('admincrud.Closed'),
            AVAILABILITY_STATUS_BUSY     => __('admincrud.Busy'),
            AVAILABILITY_STATUS_OUT_OF_SERVICE     => __('admincrud.Out Of Service')
        ];
        return ($availablityStatus !== null && isset($options[$availablityStatus])) ? $options[$availablityStatus] : $options;
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
}
