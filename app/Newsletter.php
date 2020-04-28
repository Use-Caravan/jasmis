<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Common;
use DB;
use App;

class Newsletter extends CModel
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
	protected $table = 'newsletter';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'newsletter_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'newsletter_key';
    
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

    public static function selectNewsletters()
    {
        $self = new self();
        $newsletters = self::getList()->where([$self->getTable().'.status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($newsletters,'newsletter_title','newsletter_id');
    } 
}
