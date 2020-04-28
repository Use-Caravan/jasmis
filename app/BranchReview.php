<?php

namespace App;

use Illuminate\{
    Database\Eloquent\Model,
    Database\Eloquent\SoftDeletes
};
use App\Scopes\VendorScope;
use App\Scopes\BranchScope;
use App\{
    BranchReview,
    User,
    Http\Resources\Api\V1\BranchRatingResource
};
use App;
use Auth;
use Common;
use DB;


class BranchReview extends CModel
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
	protected $table = 'branch_review';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'branch_review_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'branch_review_key';
    
    /**
     * The attributes that enable unique key generation.
     *
     * @var string
     */
    protected $keyGenerate = true;

    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token','branch_key']; 
     

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
        $self = new self();
        $query = self::select($self->getTable().'.*');
        return $query;
	}
        
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
        $self = new self();
		return self::where($self->uniqueKey, $key)->first();
    }

    public static function getBranchReviews()
    {
        $user = new User();
        $userTable = $user->getTable();
        $branchReview = new BranchReview();
        $branchReviewTable = $branchReview->getTable(); 
        $query = BranchReview::select([
            "$userTable.first_name",
            "$userTable.last_name",
            "$branchReviewTable.rating",
            "$branchReviewTable.review",
            "$branchReviewTable.approved_at"
        ])
        ->leftjoin($userTable,"$branchReviewTable.user_id","$userTable.user_id")
        ->where([
            "$branchReviewTable.approved_status" => ITEM_APPROVED,
            "$branchReviewTable.status" => ITEM_ACTIVE
            ]);    
        return $query;        
    }

    public static function getAllReviews()
    {
        $query = BranchReview::select([
            User::tableName().'.first_name',
            User::tableName().'.last_name',
            BranchReview::tableName().'.*',
           
        ])
        ->leftjoin(User::tableName(),BranchReview::tableName().'.user_id',User::tableName().'.user_id')
        ->leftjoin(Vendor::tableName(),BranchReview::tableName().'.vendor_id',Vendor::tableName().'.vendor_id')
        ->leftjoin(Branch::tableName(),BranchReview::tableName().'.branch_id',Branch::tableName().'.branch_id');
        VendorLang::selectTranslation($query);
        BranchLang::selectTranslation($query);
        return $query;        
    }

    public function approvedStatus($approvedStatus = null)
    {          
        $options = [
            REVIEW_APPROVED_STATUS_PENDING => __('admincrud.Pending'),
            REVIEW_APPROVED_STATUS_APPROVED      => __('admincrud.Approved'),
            REVIEW_APPROVED_STATUS_REJECTED  => __('admincrud.Rejected')
        ];                
        return ($approvedStatus !== null && isset($options[$approvedStatus])) ? $options[$approvedStatus] : $options;
    }
}
