<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class OfferItem extends CModel
{    

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'offer_item';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'offer_item_id';        


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['offer_id','item_id']; 


    public static function getList()
	{
        $self = new self();
        $offer = new Offer();
        $query = self::select( $self->getTable().'.*')->leftJoin(
            Offer::tableName(),
            self::tableName().'.offer_id',
            '=',
            Offer::tableName().'.offer_id'
        );
        return $query;
    }

    public static function selectItem($offerId)
    {
        $self = new self();
        $offer = new Offer();
		$editOfferItem = self::getList()->where(
			[ 
                $self->getTable().'.offer_id' => $offerId,                
                $offer->getTable().'.status' => ITEM_ACTIVE, 
            ])->get()->toArray();
			$data = [];
		foreach ($editOfferItem as $key => $value) {
			$data[] = $value['item_id'];
		}	
			return $data;
    }



    public static function selectOfferItem($offerId)
	{	
        $self = new self();
        $offer = new Offer();
		$offer = self::getList()->where(
			[
				$self->getTable().'.offer_id' => $offerId,                
            ])->get()->toArray();
			$data = [];
		foreach ($offer as $key => $value) {
			$data[] = $value['item_id'];
		}	
		return $data;
    }
}
