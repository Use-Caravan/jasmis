<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\Country;
use App;

class CountryLang extends CModel
{
   
    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'country_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'country_lang_id';

    /**
	 * The database table foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "country_id";

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
    protected $fillable = ['country_id','language_code','country_name'];

    /**
    * @param query  $query     
    * @param string $alias
    * @return $query
    */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $country = new Country();
        $tableName = $self->table;
        if($alias == ''){
            $alias = 'CL';
        }
        $selectable = $self->fillable; 
        $langForeignKey = $self->langForeignKey; 
        foreach($selectable as $key => $value){
            if($value != $langForeignKey && $value != 'language_code'){
            $selects[] = "$alias.$value";
            } 
        }
        $query->leftJoin("$tableName as $alias",function($query) use ($alias,$langForeignKey ,$country) {
            $query->on($country->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")    
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }
}
