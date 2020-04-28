<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Scopes\LanguageScope;
use App\City;
use App;

class CityLang extends CModel
{

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
    protected $table = 'city_lang';
    
    /**
	 * The database table primary key
	 *
	 * @var string
	 */
    protected $primaryKey = 'city_lang_id';

    /**
	 * The database table translation foreign key
	 *
	 * @var string
	 */
    protected $langForeignKey = "city_id";

   
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
    protected $fillable = ['city_id','language_code','city_name'];

   
    /**
     * @param query  $query     
     * @param string $alias
     * @return $query
     */
    public static function selectTranslation($query,$alias = '')
    {
        $self = new self();
        $city = new City();
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
        $query->leftJoin("$tableName as $alias",function($query) use ($alias, $langForeignKey, $city) {
            $query->on($city->getTable().".$langForeignKey", '=', "$alias.$langForeignKey")
            ->where("$alias.language_code",App::getLocale());
        })->addSelect($selects);
    }    
}
