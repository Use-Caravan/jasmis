<?php

namespace App;

use Illuminate\Database\Eloquent\{
    Model,
    SoftDeletes
};
use App\{
    Scopes\VendorScope,
    Scopes\BranchScope,
    ItemLang,
    Branch,
    BranchLang,
    Vendor,
    VendorLang,
    Cuisine,
    CusineLang,
    Category,
    CategoryLang
};
use Common;
use DB;
use App;


class Item extends CModel
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
	protected $table = 'item';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'item_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'item_key';

    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['item_name','item_image','item_description','allergic_ingredient','ingredient_group_id','cuisine_id','csrf-token'];    


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
     * Translation model to save data 
     * @return Object 
    */
    public function transModel()
    {
        return new ItemLang();
    }

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
        $self = new self();        
        $query = self::select($self->getTable().".*");
        ItemLang::selectTranslation($query);
        return $query;
	}
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{  
		return self::where(self::uniqueKey(),$key)->first();
      
    }

    public static function getAll()
    {
        $self = new self();
        $vendor = new Vendor();
        $cuisine = new Cuisine();
        $category = new Category();
        $branch = new Branch();        
        $query = self::getList()->addSelect([
            Branch::tableName().".branch_key"
        ])->leftJoin($vendor->getTable(),$self->getTable().".vendor_id",'=', $vendor->getTable().".vendor_id");
        VendorLang::selectTranslation($query);
        $query = $query->leftJoin($branch->getTable(),$self->getTable().".branch_id",'=', $branch->getTable().".branch_id");
        BranchLang::selectTranslation($query);
        $query = $query->leftJoin($category->getTable(),$self->getTable().".category_id",'=',$category->getTable().".category_id");
        CategoryLang::selectTranslation($query);        
        $query = $query->leftJoin($cuisine->getTable(),$self->getTable().".cuisine_id",'=', $cuisine->getTable().".cuisine_id");
        CuisineLang::selectTranslation($query,'CUL');
        $query = $query->where([
                Branch::tableName().".status" => ITEM_ACTIVE,
                Vendor::tableName().".status" => ITEM_ACTIVE,
                Category::tableName().".status" => ITEM_ACTIVE,
            ]);        
        $query = $query->where(function() use ($query) {
            if(request()->get('created_at') !== null) {                
                $query->whereDate(Item::tableName().".created_at",request()->get('created_at') );
            }
        });
        return $query;
    }

    /**
     * @param int $branchId return item depands on branch     
     */
    public static function getAllItems($branchId = null,$offerValue = null)
    {
        $query = self::getList();
        if($branchId !== null) {
            $query = $query->where(['branch_id' => $branchId,'status' => ITEM_ACTIVE]);
        }
        if($offerValue !== null) {
            $query = $query->where(['branch_id' => $branchId,'status' => ITEM_ACTIVE])->where('item_price','>',$offerValue);
        }
        $getAllitems = $query->whereNull('deleted_at')->get()->toArray();
        return array_column($getAllitems,'item_name','item_id');
    }


    public function approvedStatus($approvedStatus = null)
    {          
        $options = [
            ITEM_APPROVED      => __('admincrud.Approved'),
            ITEM_UNAPPROVED  => __('admincrud.Unapproved')
        ];                
        return ($approvedStatus !== null && isset($options[$approvedStatus])) ? $options[$approvedStatus] : $options;
    }

    

}
