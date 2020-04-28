<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Common;
use DB;
use App;

class Role extends CModel
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
	protected $table = 'role';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'role_key';
    
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
    protected $guarded = ['csrf-token']; 
	   
	
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
        $query = self::select($self->getTable().'.*')->where('user_type',ROLE_USER_ADMIN);
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
    
    public static function getRoles()
    {
        $roleName = self::getList()->where(['status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($roleName,'role_name','role_id');
    }

     public static function getRoleFilter()
    {  
        $roleNames = self::getList()->get()->toArray();
        return array_column($roleNames,'role_name','role_id');
    }
    
}
