<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Common;
use DB;
use App;
use App\Cuisine;
use App\Branch;
use App\CuisineLang;

class BranchCuisine extends CModel
{
	    /**
		 * The database table used by the model.		 
		 * @var string
		 */
		protected $table = 'branch_cuisine';	

		/**
	     * Indicates if the model should be timestamped.	     
	     * @var bool
	     */
	    public $timestamps = false;
	       
	    /**
	     * The attributes that primary key.	     
	     * @var string
	     */
	    protected $primaryKey = 'cuisine_id';		   
		   
		/**
		 * 
		 * Protect the column to insert
		 * @var array
		 */
	    protected $fillable = ['cuisine_id','branch_id'];    
	    

	    public static function tableName()
	    {
	        $self = new self();
	        return $self->table;
	    }

	    public static function getList()
		{
            $self = new self();
            $cuisine = new Cuisine();
            $query = self::select($self->getTable().'.*')->leftJoin(
                $cuisine->getTable(),
                $self->getTable().'.cuisine_id',
                '=',
                $cuisine->getTable().'.cuisine_id'
            );
            return $query;
   		}
  	
	    public static function selectCuisine($branchId)
	    {	
            $self = new self();
            $cuisine = new Cuisine();
	    	$editCuisine = self::getList()->where(
			[ 
				$self->getTable().'.branch_id' => $branchId,
                $cuisine->getTable().'.status' => ITEM_ACTIVE, 
            ])->get()->toArray();
            
			$data = [];
			foreach ($editCuisine as $key => $value) {
				$data[] = $value['cuisine_id'];
			}	
				return $data;
		}
  	
  	public static function getBranchCuisine($branchId)
    { 
        $self = new self();
        $cuisine = new Cuisine();
        $branch = new Branch();
    	$query = self::select($cuisine->getTable().'.cuisine_id')->leftJoin($branch->getTable(),$self->getTable().'.branch_id','=', 
        $branch->getTable().'.branch_id')
        ->leftJoin($cuisine->getTable(),$self->getTable().'.cuisine_id','=', 
        $cuisine->getTable().'.cuisine_id');
        CuisineLang::selectTranslation($query);
        $query = $query->where([$branch->getTable().'.branch_id' => $branchId])->get()->toArray();
        return array_column($query,'cuisine_name','cuisine_id');	
                
    }
}
