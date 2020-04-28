<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Common;
use DB;
use App;

class Enquiry extends CModel
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
	protected $table = 'enquiry';	

    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'enquiry_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'enquiry_key';

    /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;

     /**
     * Table fillable column names
     *
     * @var array
     */

    protected $fillable = ['first_name','email','phone_number','subject','comments'];
	
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
	public static function findByKey($key)
	{  
		return self::where(self::uniqueKey(),$key)->first();
      
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
}
