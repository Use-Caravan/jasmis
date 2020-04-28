<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;

class ItemCuisine extends CModel
{    

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'item_cuisine';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'item_cuisine_id';

    /**
     * The attributes that disable timestamp
     *
     * @var string
     */
    public $timestamps = false;
    
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['item_id','cuisine_id','status'];    

   
    public static function uniqueKey()
    {
        $self = new self();
        return $self->uniqueKey;
    } 

    
    public static function getExistsCuisines($itemId)
    {
        $self = new self();
        $itemCuisines = new ItemCuisine();
        $query = self::select('cuisine_id')
                ->where(['item_id' => $itemId])
                ->get()->toArray();
            return array_column($query,'cuisine_id');
    }   
}
