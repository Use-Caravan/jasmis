<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model
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
	protected $table = 'activitylog';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'activitylog_id';
    
    /** 
     * The attribute those are fillables 
     */
    protected $fillable = ['log_name','description','subject_id','subject_type','causer_id','causer_type','causer_name','properties'];

    /**	 	
	 * @var query
	 */
	public static function getList()
	{ 
        $self = new self();        
        $query = self::select($self->getTable().'.*');        
        return $query;
    }	
}
