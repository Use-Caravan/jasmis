<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;
use App\Branch;

class UserWishlist extends CModel
{
    
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_wishlist';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'user_wishlist_id';
    
    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf_token']; 
	       
   /**
	 * Get Unique key to generate key
	 * @return string
	*/
    public static function primaryKey()
    {
        $self = new self();
        return $self->primaryKey;
    } 


    /**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{ 
		return self::where(self::primaryKey(), $key)->first();
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
        
	 public static function getAllWishlist()
    {
        $wishlist = self::getList()
                   ->addSelect(User::tableName().'.first_name',User::tableName().'.last_name',User::tableName().'.username',User::tableName().'.email')
                   ->leftjoin(User::tableName(),self::tableName().'.user_id',User::tableName().'.user_id')
                   ->leftjoin(Branch::tableName(),self::tableName().'.branch_id',Branch::tableName().'.branch_id');
                   BranchLang::selectTranslation($wishlist);
                   
        return $wishlist;
                   
    } 
}
