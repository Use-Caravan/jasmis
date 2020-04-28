<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Common;
use DB;
use App;

class EmailQueue extends CModel
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
	protected $table = 'email_queue';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'email_queue_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'email_queue_key';
    
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
    
	
}
