<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DeliveryArea;
use Common;
use DB;
use App;

class BranchDeliveryArea extends CModel
{

	/**
	 * The database table used by the model.	 
	 * @var string
	 */
	protected $table = 'branch_delivery_area';	
       
    /**
     * The attributes that primary key.     
     * @var string
     */
    protected $primaryKey = 'branch_delivery_area_id';

    /**
     * Indicates if the model should be timestamped.     
     * @var bool
     */
    public $timestamps = false;
           

	/**	 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['delivery_area_id','branch_id'];    
    

    public static function getList()
	{
        $self = new self();
        $deliveryArea = new DeliveryArea();
        $query = self::select( $self->getTable().'.*')->leftJoin(
            $deliveryArea->getTable(),
            $self->getTable().'.delivery_area_id',
            '=',
            $deliveryArea->getTable().'.delivery_area_id'
        );
        return $query;
    }
  
	public static function selectDeliveryArea($branchId)
	{	
        $self = new self();
        $deliveryArea = new DeliveryArea();
		$editDeliveryArea = self::getList()->where(
			[ 
				$self->getTable().'.branch_id' => $branchId,
                $deliveryArea->getTable().'.status' => ITEM_ACTIVE, 
            ])->get()->toArray();
			$data = [];
		foreach ($editDeliveryArea as $key => $value) {
			$data[] = $value['delivery_area_id'];
		}	
			return $data;
    }
}
