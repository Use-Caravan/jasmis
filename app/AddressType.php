<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AddressTypeLang;
use App\Scopes\LanguageScope;
use Common;
use DB;
use App;

class AddressType extends CModel
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
	protected $table = 'address_type';	
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'address_type_id';
    
    /**
     * The attributes that table unique key.
     *
     * @var string
     */
	protected $uniqueKey = 'address_type_key';
    
    /**
     * Table keygenerate variable 
     * 
     * @var string
     */
    protected $keyGenerate = true;
	   
	/**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $guarded = ['address_type_name','csrf-token'];    

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
	 * Translation model to save data 
	 * @return Object 
	*/
    public function transModel()
    {
        return new AddressTypeLang();
    }    
    
	/**
	 *
	 * @var query
	 */
	public static function findByKey($key)
	{
		return self::where(self::uniqueKey(), $key)->first();
    }

    
    /**	 
	 *
	 * @var query
	 */
	public static function getList()
	{ 
        $self = new self();        
        $query = self::select($self->getTable().'.*');
        AddressTypeLang::selectTranslation($query);        
        return $query;
	}

    public static function getAddressType()
    {        
        $addressTypes = self::getList()->where(['status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($addressTypes,'address_type_name','address_type_id');
    }
    public static function getExistAddressType($id)
    {
        $existsAddressTypes = self::getList()->where([self::tableName().'.address_type_id'=>$id,'status' => ITEM_ACTIVE])->get()->toArray();
        return array_column($existsAddressTypes,'address_type_name','address_type_id');
    }
}
