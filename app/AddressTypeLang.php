<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App;
use Illuminate\Database\Eloquent\Builder;
use App\AddressType;
use App\Scopes\LanguageScope;

class AddressTypeLang extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'address_type_lang';
    
    /**
	 * The database table primary key.
	 *
	 * @var string
	 */
    protected $primaryKey = 'address_type_lang_id';

    /**
	 * The database table translation foreign key.
	 *
	 * @var string
	 */
    protected $langForeignKey = "address_type_id";

	/**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table fillable column names
     *
     * @var array
     */
    protected $fillable = ['address_type_id', 'language_code', 'address_type_name'];


     /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query, $alias = '')
    {
        $self = new self();
        $addressType = new AddressType();
        
        $tableName = $self->getTable();
        if($alias == ''){
            $alias = 'ATL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $addressType) {
            $query->on($addressType->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })
        ->addSelect($selects);
    }
}
