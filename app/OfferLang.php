<?php

namespace App;
use App;


class OfferLang extends CModel
{    

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'offer_lang';
       
    /**
     * The attributes that primary key.
     *
     * @var string
     */
    protected $primaryKey = 'offer_lang_id';            


    /**
     * The database table translation foreign key
     *
     * @var string
     */
    protected $langForeignKey = "offer_id";


    protected $keyGenerate = false;


    /**
	 * 
	 * Protect the column to insert
	 * @var array
	 */
    protected $fillable = ['offer_name','offer_banner']; 


    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


     /**
     * Table fillable file columns and path
     *
     * @var array
     */
    protected $fileInput = [ 'offer_banner' => [ 'path' => OFFER_BANNER_PATH ] ];


    public static function selectTranslation($query,$alias = '')
    {                
        $tableName = self::tableName();
        if($alias == ''){
            $alias = 'OL';
        }
        $selectable = (new self)->fillable;         
        $langForeignKey = (new self)->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias,$langForeignKey) {
            $query->on(Offer::tableName().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
	   	    
}
