<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\
{
    Scopes\VendorScope,
    Scopes\BranchScope,
    OfferLang
};


class Offer extends CModel
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
	protected $table = 'offer';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'offer_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
    protected $uniqueKey = 'offer_key';
    
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
    protected $guarded = ['csrf-token','offer_name','offer_banner','item_id']; 


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
        return new OfferLang();
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
        OfferLang::selectTranslation($query);
        return $query;
    }

}

