<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\
{
    Scopes\VendorScope,
    Scopes\BranchScope,
    CorporateOfferLang
};


class CorporateOffer extends CModel
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
	protected $table = 'corporate_offer';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'corporate_offer_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'corporate_offer_key';
    
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
    protected $guarded = ['csrf-token','offer_name','offer_banner','offer_description']; 


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new VendorScope());
        static::addGlobalScope(new BranchScope());
    }

    
    /**
     * Translation model to save data 
     * @return Object 
    */
    public function transModel()
    {
        return new CorporateOfferLang();
    }

	
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
        $self  = new self();
        $query = self::select($self->getTable().".*");
        CorporateOfferLang::selectTranslation($query);
        return $query;
    }

    public function offerType($offerType = null)
    {
        $options = [
            CORPORATE_OFFER_TYPE_QUANTITY      => __('admincrud.Quantity'),
            CORPORATE_OFFER_TYPE_AMOUNT  => __('admincrud.Amount')
        ];
        return ($offerType !== null && isset($options[$offerType])) ? $options[$offerType] : $options;
    }

}

