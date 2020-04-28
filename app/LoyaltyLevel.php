<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\LoyaltyLevelLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class LoyaltyLevel extends CModel
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
	protected $table = 'loyalty_level';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'loyalty_level_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'loyalty_level_key';

     /**
     * The attributes that enable table unique key.
     *
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['loyalty_level_name','csrf-token'];    
    

    /**
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new LoyaltyLevelLang();
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
        $self = new self();
        $query = self::select($self->getTable().'.*');
        LoyaltyLevelLang::selectTranslation($query);        
        return $query;
	}
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }
}
