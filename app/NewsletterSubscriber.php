<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Common;
use DB;
use App;

class NewsletterSubscriber extends CModel
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
	protected $table = 'newsletter_subscriber';	

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'newsletter_subscriber_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'newsletter_subscriber_key';

    /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;
	
    /**
	 * Get Unique key to generate key
	 * @return string
	*/


    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token']; 


	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email'];

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
		return self::where(self::uniqueKey(),$key)->first();
      
    }

    public static function selectSubscriberEMail() 
    {
        $self = new self();
        $emailList = self::getList()->where([$self->getTable().'.status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($emailList,'email','newsletter_subscriber_id');
    }

}
