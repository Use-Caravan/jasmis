<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CModel;
use Common;
use DB;
use App;


class OrderItem extends CModel
{        
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'order_item';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'order_item_id';
        
    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['csrf-token']; 	  
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
